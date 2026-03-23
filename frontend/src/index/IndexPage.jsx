import { useEffect, useState } from 'react'
import { loadLanguage } from '../i18n/loadLanguage'
import { clearSession, loadSession, saveSession } from '../storage/session'

export default function IndexPage({ onStart }) {
  const [loading, setLoading] = useState(true)
  const [submitting, setSubmitting] = useState(false)
  const [error, setError] = useState('')

  const [lang, setLang] = useState('es')
  const [t, setT] = useState(null)

  const [playerName, setPlayerName] = useState('')
  const [difficulty, setDifficulty] = useState('facil')
  const [permadeath, setPermadeath] = useState(false)
  const [infoText, setInfoText] = useState('')

  useEffect(() => {
    document.body.className = 'body-index'
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
        setLang(nextLang)

        if (session?.playerName) setPlayerName(session.playerName)
        if (session?.difficulty) setDifficulty(session.difficulty)
        if (typeof session?.permadeath === 'boolean') setPermadeath(session.permadeath)

        const translations = await loadLanguage(nextLang)
        if (!alive) return
        setT(translations)
        if (translations?.tituloIndex) document.title = translations.tituloIndex
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

  async function changeLang(nextLang) {
    setLang(nextLang)
    try {
      const translations = await loadLanguage(nextLang)
      setT(translations)
      if (translations?.tituloIndex) document.title = translations.tituloIndex
      saveSession({ lang: nextLang })
    } catch (e) {
      setError(e?.message || 'Error')
    }
  }

  async function handleStart() {
    setError('')

    if (!playerName.trim()) {
      setInfoText(t?.infoNombreObligatorio || 'Por favor, ingresa tu nombre para continuar.')
      return
    }

    setInfoText('')
    setSubmitting(true)

    try {
      saveSession({
        playerName,
        difficulty,
        permadeath,
        lang,
      })

      onStart?.({ playerName, difficulty, permadeath, lang })
    } catch (e) {
      setError(e?.message || 'Error')
    } finally {
      setSubmitting(false)
    }
  }

  return (
    <>
      <header>
        <img src="/IMG/Marvel_Logo.png" alt="Marvel Logo" className="marvel-logo" />
        <div className="user-info">
          {playerName ? (
            <>
              <span className="player-name">{t?.jugador || 'Jugador'}: {playerName}</span>
              <a
                href="#"
                className="logout-link"
                onClick={(e) => {
                  e.preventDefault()
                  clearSession()
                  setPlayerName('')
                }}
              >
                {t?.cerrarSesion || 'Cerrar sesión'}
              </a>
            </>
          ) : null}
        </div>

        <form method="post" id="langForm" className="lang-selector" onSubmit={(e) => e.preventDefault()}>
          <select
            name="lang"
            id="langSelect"
            value={lang}
            onChange={(e) => changeLang(e.target.value)}
            disabled={loading || submitting}
          >
            <option value="es">{t?.idioma1Index || 'Español'}</option>
            <option value="ca">{t?.idioma2Index || 'Català'}</option>
            <option value="en">{t?.idioma3Index || 'English'}</option>
          </select>
        </form>
      </header>

      <div className="div-margin"></div>

      <div className="hero-container">
        <img src="/IMG/IndexImg001.png" alt="Iron Man" className="side-img left-img" />

        <section>
          <h1>{t?.tituloh1Index || 'MARVELTYPE'}</h1>
          <p id="gameDescription">{t?.descripcionIndex || ''}</p>

          <div id="nameArea">
            <input
              type="text"
              id="inputName"
              placeholder={t?.placeholderIndex || 'Tu nombre'}
              value={playerName}
              onChange={(e) => setPlayerName(e.target.value)}
              disabled={loading || submitting}
            />
            <p id="infoText">{infoText}</p>
          </div>

          <select
            id="selectDifficulty"
            value={difficulty}
            onChange={(e) => setDifficulty(e.target.value)}
            disabled={loading || submitting}
          >
            <option value="facil">{t?.option1Index || 'Fácil'}</option>
            <option value="medio">{t?.option2Index || 'Medio'}</option>
            <option value="dificil">{t?.option3Index || 'Difícil'}</option>
          </select>

          <label>
            <input
              type="checkbox"
              id="checkbox"
              value="1"
              checked={permadeath}
              onChange={(e) => setPermadeath(e.target.checked)}
              disabled={loading || submitting}
            />
            {t?.labelPermadeath || 'Permadeath'}
            <span className="tooltip">?
              <span className="tooltiptext">{t?.tooltipPermadeath || ''}</span>
            </span>
          </label>

          <br />
          <button type="button" id="startGameButton" onClick={handleStart} disabled={loading || submitting}>
            {submitting ? (t?.cargando || 'Cargando…') : (t?.botonIndex || 'Start')}
          </button>

          {error ? (
            <div className="no-js-warning">
              {error}
            </div>
          ) : null}
        </section>

        <img src="/IMG/IndexImg002.png" alt="Thanos" className="side-img right-img" />
      </div>
    </>
  )
}
