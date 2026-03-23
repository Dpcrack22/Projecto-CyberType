import { useState } from 'react'
import { getSentences } from '../api/game'

export default function GameSandbox() {
  const [difficulty, setDifficulty] = useState('facil')
  const [lang, setLang] = useState('es')
  const [loading, setLoading] = useState(false)
  const [error, setError] = useState('')
  const [items, setItems] = useState([])

  async function load() {
    setLoading(true)
    setError('')
    try {
      const data = await getSentences({ difficulty, lang })
      setItems(Array.isArray(data) ? data : [])
    } catch (e) {
      setError(e?.message || 'Error')
      setItems([])
    } finally {
      setLoading(false)
    }
  }

  return (
    <section>
      <h2>Juego (Sandbox)</h2>

      <div style={{ display: 'flex', gap: 8, flexWrap: 'wrap', alignItems: 'center' }}>
        <label>
          Dificultad:{' '}
          <select value={difficulty} onChange={(e) => setDifficulty(e.target.value)}>
            <option value="facil">facil</option>
            <option value="medio">medio</option>
            <option value="dificil">dificil</option>
          </select>
        </label>

        <label>
          Idioma:{' '}
          <select value={lang} onChange={(e) => setLang(e.target.value)}>
            <option value="es">es</option>
            <option value="ca">ca</option>
            <option value="en">en</option>
          </select>
        </label>

        <button onClick={load} disabled={loading}>
          {loading ? 'Cargando…' : 'Cargar frases'}
        </button>
      </div>

      {error ? <p style={{ color: 'crimson' }}>{error}</p> : null}

      <ul>
        {items.map((it, idx) => (
          <li key={idx} style={{ marginBottom: 12 }}>
            <div><strong>Frase:</strong> {it.frase}</div>
            {it.imagen ? (
              <div>
                <strong>Imagen:</strong>{' '}
                <img
                  alt=""
                  src={`/backend/IMG/${it.imagen}`}
                  style={{ maxWidth: 240, display: 'block', marginTop: 6 }}
                />
              </div>
            ) : null}
          </li>
        ))}
      </ul>
    </section>
  )
}
