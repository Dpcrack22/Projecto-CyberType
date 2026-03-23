import { useEffect, useMemo, useRef, useState } from 'react'
import { loadLanguage } from '../i18n/loadLanguage'
import { clearSession, loadSession } from '../storage/session'
import { loadSentences } from '../game/loadSentences'

function formatSeconds(ms) {
  const s = Math.floor(ms / 1000)
  return `${s}`
}

export default function PlayPage({ onBack }) {
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState('')

  const [t, setT] = useState(null)
  const [lang, setLang] = useState('es')
  const [playerName, setPlayerName] = useState('')
  const [difficulty, setDifficulty] = useState('medio')
  const [permadeath, setPermadeath] = useState(false)

  const [items, setItems] = useState([]) // [{frase, imagen}]
  const [idx, setIdx] = useState(0)
  const [pos, setPos] = useState(0)
  const [marks, setMarks] = useState([]) // 'correcta' | 'incorrecta'

  // Mantener estado crítico en refs para evitar problemas con batching cuando el
  // usuario escribe muy rápido.
  const itemsRef = useRef([])
  const idxRef = useRef(0)
  const posRef = useRef(0)
  const marksRef = useRef([])

  // Scoring / bonus / multiplier (legacy scriptPlay.js & scriptPlayPerma.js)
  const [score, setScore] = useState(0)
  const scoreRef = useRef(0)
  const [bonusCount, setBonusCount] = useState(0)
  const bonusCountRef = useRef(0)
  const [multiplier, setMultiplier] = useState(1)
  const multiplierRef = useRef(1)
  const rightStreakRef = useRef(0)
  const wrongStreakRef = useRef(0)

  const [lives, setLives] = useState(5)
  const livesRef = useRef(5)

  const [bonusText, setBonusText] = useState('')
  const [showBonus, setShowBonus] = useState(false)
  const bonusTimerRef = useRef(null)

  // Barra 3s: cuando se agota, multiplicador vuelve a 1
  const [comboFillPct, setComboFillPct] = useState(100)
  const comboTimerRef = useRef(null)
  const timeLeftRef = useRef(3)

  const [phase, setPhase] = useState('countdown') // 'countdown' | 'playing' | 'done'
  const [countdown, setCountdown] = useState(3)

  const startedAtRef = useRef(0)
  const [elapsedMs, setElapsedMs] = useState(0)

  const hiddenInputRef = useRef(null)
  const composingRef = useRef(false)

  const current = items[idx] || null
  const phrase = (current?.frase || '')
  const phraseChars = useMemo(() => Array.from(phrase), [phrase])
  const total = items.length

  useEffect(() => {
    itemsRef.current = items
  }, [items])

  useEffect(() => {
    document.body.className = 'body-play'
    return () => {
      document.body.className = ''
    }
  }, [])

  useEffect(() => {
    let alive = true

    async function boot() {
      setLoading(true)
      setError('')
      try {
        const session = loadSession() || {}
        const nextLang = session?.lang || 'es'
        const nextDifficulty = session?.difficulty || 'medio'
        const nextName = session?.playerName || ''
        const nextPerma = !!session?.permadeath

        setLang(nextLang)
        setDifficulty(nextDifficulty)
        setPlayerName(nextName)
        setPermadeath(nextPerma)

        const translations = await loadLanguage(nextLang)
        if (!alive) return
        setT(translations)
        if (translations?.tituloPlay) document.title = translations.tituloPlay

        const loaded = await loadSentences({ lang: nextLang, difficulty: nextDifficulty })
        if (!alive) return
        setItems(loaded)
        itemsRef.current = loaded

        setIdx(0)
        idxRef.current = 0
        setPos(0)
        posRef.current = 0
        setMarks([])
        marksRef.current = []

        setScore(0)
        scoreRef.current = 0
        setBonusCount(0)
        bonusCountRef.current = 0
        setMultiplier(1)
        multiplierRef.current = 1
        rightStreakRef.current = 0
        wrongStreakRef.current = 0

        // Permadeath: 5 vidas. Normal mode oculta el UI pero mantenemos el valor.
        setLives(5)
        livesRef.current = 5

        setPhase('countdown')
        setCountdown(3)
      } catch (e) {
        if (!alive) return
        setError(e?.message || 'Error')
      } finally {
        if (!alive) return
        setLoading(false)
      }
    }

    boot()
    return () => {
      alive = false
    }
  }, [])

  useEffect(() => {
    if (loading || error) return
    if (!items.length) return

    if (phase !== 'countdown') return

    const interval = setInterval(() => {
      setCountdown((c) => c - 1)
    }, 1000)

    return () => clearInterval(interval)
  }, [loading, error, items.length, phase])

  useEffect(() => {
    if (phase !== 'countdown') return
    if (countdown > 0) return

    // 0 => show YA!, then next tick start.
    if (countdown === 0) {
      const timeout = setTimeout(() => {
        setCountdown(-1)
      }, 1000)
      return () => clearTimeout(timeout)
    }

    // countdown < 0 => start game
    setPhase('playing')
    startedAtRef.current = performance.now()
    setElapsedMs(0)

    // iniciar barra 3s / combo
    resetComboTimer()

    // focus hidden input
    queueMicrotask(() => {
      hiddenInputRef.current?.focus()
    })
  }, [countdown, phase])

  useEffect(() => {
    if (phase !== 'playing') return
    const interval = setInterval(() => {
      setElapsedMs(performance.now() - startedAtRef.current)
    }, 100)
    return () => clearInterval(interval)
  }, [phase])

  useEffect(() => {
    // reset marks when phrase changes
    setMarks([])
    marksRef.current = []
    setPos(0)
    posRef.current = 0
    queueMicrotask(() => {
      hiddenInputRef.current?.focus()
    })
  }, [idx])

  useEffect(() => {
    // limpiar timers al salir/terminar
    return () => {
      if (comboTimerRef.current) {
        clearInterval(comboTimerRef.current)
        comboTimerRef.current = null
      }
      if (bonusTimerRef.current) {
        clearTimeout(bonusTimerRef.current)
        bonusTimerRef.current = null
      }
    }
  }, [])

  useEffect(() => {
    if (phase !== 'playing') {
      if (comboTimerRef.current) {
        clearInterval(comboTimerRef.current)
        comboTimerRef.current = null
      }
      setComboFillPct(100)
    }
  }, [phase])

  function showBonusMessage(nextMultiplier) {
    setBonusText(`Bonus x${nextMultiplier}!`)
    setShowBonus(true)
    if (bonusTimerRef.current) clearTimeout(bonusTimerRef.current)
    bonusTimerRef.current = setTimeout(() => {
      setShowBonus(false)
    }, 1500)
  }

  function resetComboTimer() {
    if (comboTimerRef.current) clearInterval(comboTimerRef.current)
    timeLeftRef.current = 3
    setComboFillPct(100)

    comboTimerRef.current = setInterval(() => {
      timeLeftRef.current -= 0.1
      const pct = Math.max(0, (timeLeftRef.current / 3) * 100)
      setComboFillPct(pct)
      if (timeLeftRef.current <= 0) {
        clearInterval(comboTimerRef.current)
        comboTimerRef.current = null
        // legacy: si se agota el tiempo, multiplicador vuelve a 1
        multiplierRef.current = 1
        setMultiplier(1)
      }
    }, 100)
  }

  function applyEasterEgg(isCorrect) {
    let nextScore = scoreRef.current
    let nextBonus = bonusCountRef.current
    let nextMultiplier = multiplierRef.current

    if (isCorrect) {
      wrongStreakRef.current = 0
      rightStreakRef.current += 1
      nextScore += 50
      if (rightStreakRef.current === 5) {
        rightStreakRef.current = 0
        nextScore += 200
        nextBonus += 1
        nextMultiplier += 1
        showBonusMessage(nextMultiplier)
      }
    } else {
      rightStreakRef.current = 0
      wrongStreakRef.current += 1
      nextScore -= 20
      if (wrongStreakRef.current >= 2 && nextMultiplier > 1) {
        nextMultiplier -= 1
        showBonusMessage(nextMultiplier)
      }
      if (wrongStreakRef.current === 5) {
        nextScore -= 200
        nextBonus -= 1
      }
    }

    scoreRef.current = nextScore
    bonusCountRef.current = nextBonus
    multiplierRef.current = nextMultiplier
    setScore(nextScore)
    setBonusCount(nextBonus)
    setMultiplier(nextMultiplier)
  }

  function endGame() {
    setPhase('done')
    if (comboTimerRef.current) {
      clearInterval(comboTimerRef.current)
      comboTimerRef.current = null
    }
  }

  function updateWithChar(ch) {
    if (phase !== 'playing') return
    const activeItems = itemsRef.current
    const activeIdx = idxRef.current
    const active = activeItems[activeIdx]
    const activePhrase = active?.frase || ''
    const activeChars = Array.from(activePhrase)

    if (!activeChars.length) return
    if (posRef.current >= activeChars.length) return

    const expected = activeChars[posRef.current]
    const isCorrect = ch === expected

    // puntos base por tecla (legacy)
    const baseOk = permadeath ? 25 : 10
    const baseBad = permadeath ? -10 : -5
    const baseDelta = isCorrect ? baseOk : baseBad
    const nextScoreBase = scoreRef.current + baseDelta
    scoreRef.current = nextScoreBase
    setScore(nextScoreBase)

    // barra 3s siempre se reinicia con cada tecla
    resetComboTimer()

    // rachas/bonus/multiplicador
    applyEasterEgg(isCorrect)

    // permadeath: restar 1 vida por fallo
    if (permadeath && !isCorrect) {
      const nextLives = Math.max(0, livesRef.current - 1)
      livesRef.current = nextLives
      setLives(nextLives)
      if (nextLives === 0) {
        endGame()
        return
      }
    }

    // marcar letra
    const nextMarks = marksRef.current.slice()
    nextMarks[posRef.current] = isCorrect ? 'correcta' : 'incorrecta'
    marksRef.current = nextMarks
    setMarks(nextMarks)

    const nextPos = posRef.current + 1
    posRef.current = nextPos
    setPos(nextPos)

    if (nextPos >= activeChars.length) {
      // siguiente frase
      setTimeout(() => {
        const nextIdx = idxRef.current + 1
        if (nextIdx >= activeItems.length) {
          endGame()
          return
        }
        idxRef.current = nextIdx
        setIdx(nextIdx)
      }, 250)
    }
  }

  function onKeyDown(e) {
    if (phase !== 'playing') return
    if (e.key === 'Backspace' || e.key === 'Enter' || e.key === 'Tab') {
      e.preventDefault()
      return
    }
    if (e.key === 'Dead' || e.key.length !== 1) return
    e.preventDefault()
    updateWithChar(e.key)
  }

  function onCompositionStart() {
    composingRef.current = true
  }

  function onCompositionEnd(e) {
    composingRef.current = false
    const data = e.data || ''
    for (const ch of Array.from(data)) updateWithChar(ch)
  }

  function onInput(e) {
    if (composingRef.current) return
    const data = e.data || ''
    if (data && data.length === 1) updateWithChar(data)
    if (hiddenInputRef.current) hiddenInputRef.current.value = ''
  }

  const showYa = phase === 'countdown' && countdown === 0
  const showCountdownNumber = phase === 'countdown' && countdown > 0
  const showGame = phase === 'playing'

  const progressLabel = total ? `Frase ${Math.min(idx + 1, total)} / ${total}` : 'Frase 0 / 0'
  const progressPct = total ? Math.round((Math.min(idx + 1, total) / total) * 100) : 0

  const vidasToShow = permadeath ? lives : 0
  const finalScore = Math.round(score * multiplier)

  return (
    <>
      <header>
        <img src="/IMG/Marvel_Logo.png" alt="Marvel Logo" className="marvel-logo" />
        <div id="vidas" style={{ display: permadeath ? 'block' : 'none' }}>
          {Array.from({ length: 5 }).map((_, i) => (
            <img
              key={i}
              src="/IMG/escudo.png"
              className="vida"
              alt="vida"
              style={{ display: i < vidasToShow ? 'inline' : 'none' }}
            />
          ))}
        </div>
        <div id="tiempoTranscurrido" style={{ display: showGame ? 'block' : 'none' }}>
          {t?.tiempoPlay ? `${t.tiempoPlay}: ${formatSeconds(elapsedMs)} s` : `Tiempo: ${formatSeconds(elapsedMs)} s`}
        </div>
        <div className="user-info">
          {playerName ? (
            <span className="player-name">{t?.jugador || 'Jugador'}: {playerName}</span>
          ) : null}
          <a
            href="#"
            className="logout-link"
            onClick={(e) => {
              e.preventDefault()
              clearSession()
              onBack?.()
            }}
          >
            {t?.cerrarSesion || 'Cerrar sesión'}
          </a>
        </div>
      </header>

      <h1 id="titulo-play" style={{ display: showGame ? 'block' : 'none' }}>
        {t?.tituloh1Play || ''}
      </h1>
      <h1 id="titulo-prepara" style={{ display: showGame ? 'none' : 'block' }}>
        {t?.cuentaAtrasPlay || ''}
      </h1>

      <div id="contador">
        {showCountdownNumber ? countdown : (showYa ? (t?.yaPlay || 'YA!') : '')}
      </div>

      <div id="imageContainer" style={{ display: showGame && current?.imagen ? 'flex' : 'none' }}>
        <img id="fraseImg" src={current?.imagen ? `/IMG/${current.imagen}` : ''} alt="Imagen asociada" />
      </div>

      <div id="fraseContainer" style={{ display: showGame ? 'block' : 'none' }} onClick={() => hiddenInputRef.current?.focus()}>
        <div id="frase">
          {phraseChars.map((ch, i) => {
            const cls = [
              marks[i] || '',
              i === pos ? 'currentLetter' : '',
            ].filter(Boolean).join(' ')
            return (
              <span key={i} className={cls}>{ch}</span>
            )
          })}
        </div>
      </div>

      <div
        id="bonusMessage"
        style={{ display: showBonus ? 'block' : 'none' }}
      >
        {bonusText}
      </div>

      <div id="progressContainer" style={{ display: showGame ? 'block' : 'none' }}>
        <div id="progressLabel">{progressLabel}</div>
        <div id="progressBar">
          <div id="progressFill" style={{ width: `${progressPct}%` }}></div>
        </div>
      </div>

      <div id="barraProgresoTiempo" style={{ display: showGame ? 'block' : 'none' }}>
        <div id="tiempoRestanteFill" style={{ width: `${comboFillPct}%` }}></div>
      </div>

      <input
        ref={hiddenInputRef}
        id="hiddenInput"
        type="text"
        autoComplete="off"
        autoCorrect="off"
        autoCapitalize="off"
        spellCheck={false}
        onKeyDown={onKeyDown}
        onInput={onInput}
        onCompositionStart={onCompositionStart}
        onCompositionEnd={onCompositionEnd}
        style={{ position: 'absolute', left: '-9999px', width: 1, height: 1, opacity: 0 }}
      />

      {loading ? <div className="no-js-warning">Cargando…</div> : null}
      {error ? <div className="no-js-warning">{error}</div> : null}

      {(!loading && !error && phase === 'done') ? (
        <div className="no-js-warning">
          {(t?.finPlay || 'Partida terminada')}
          <div style={{ marginTop: 8 }}>
            <div>{t?.puntuacionGameOver ? `${t.puntuacionGameOver}: ${finalScore}` : `Puntuación final: ${finalScore}`}</div>
            <div>{t?.bonusGameOver ? `${t.bonusGameOver}: ${bonusCount}` : `Bonus: ${bonusCount}`}</div>
            <div>{t?.labelMultiplicador ? `${t.labelMultiplicador}: x${multiplier}` : `Multiplicador: x${multiplier}`}</div>
          </div>
          <div style={{ marginTop: 12 }}>
            <button onClick={() => onBack?.()}>Volver</button>
          </div>
        </div>
      ) : null}
    </>
  )
}
