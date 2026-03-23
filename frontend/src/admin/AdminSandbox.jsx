import { useState } from 'react'
import { deleteAdminSentence, listAdminSentences } from '../api/adminSentences'

export default function AdminSandbox() {
  const [lang, setLang] = useState('es')
  const [loading, setLoading] = useState(false)
  const [error, setError] = useState('')
  const [items, setItems] = useState([])

  async function load() {
    setLoading(true)
    setError('')
    try {
      const data = await listAdminSentences({ lang })
      setItems(Array.isArray(data?.items) ? data.items : [])
    } catch (e) {
      setError(e?.message || 'Error')
      setItems([])
    } finally {
      setLoading(false)
    }
  }

  async function onDelete(it) {
    if (!confirm(`Eliminar frase?\n\n${it.frase}`)) return

    setLoading(true)
    setError('')
    try {
      await deleteAdminSentence({
        dificultad: it.dificultad,
        frase: it.raw,
        lang,
      })
      await load()
    } catch (e) {
      setError(e?.message || 'Error')
    } finally {
      setLoading(false)
    }
  }

  return (
    <section>
      <h2>Admin (Sandbox)</h2>
      <p style={{ opacity: 0.8 }}>
        Nota: para que esto funcione debes iniciar sesión en el backend primero:
        <br />
        <a href="http://localhost:8000/admin/login.php" target="_blank" rel="noreferrer">
          http://localhost:8000/admin/login.php
        </a>
      </p>

      <div style={{ display: 'flex', gap: 8, flexWrap: 'wrap', alignItems: 'center' }}>
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

      <table style={{ width: '100%', marginTop: 12, borderCollapse: 'collapse' }}>
        <thead>
          <tr>
            <th align="left">Dificultad</th>
            <th align="left">Frase</th>
            <th align="left">Acciones</th>
          </tr>
        </thead>
        <tbody>
          {items.map((it) => (
            <tr key={it.id}>
              <td style={{ padding: '6px 0' }}>{it.dificultad}</td>
              <td style={{ padding: '6px 0' }}>{it.frase}</td>
              <td style={{ padding: '6px 0' }}>
                <button onClick={() => onDelete(it)} disabled={loading}>
                  Eliminar
                </button>
              </td>
            </tr>
          ))}
          {items.length === 0 ? (
            <tr>
              <td colSpan={3} style={{ padding: '8px 0', opacity: 0.7 }}>
                (sin datos)
              </td>
            </tr>
          ) : null}
        </tbody>
      </table>
    </section>
  )
}
