export async function loadLanguage(lang = 'es') {
  const safe = ['es', 'ca', 'en'].includes(lang) ? lang : 'es'
  const resp = await fetch(`/lang/${safe}.txt`, { cache: 'no-store' })
  if (!resp.ok) throw new Error(`No se pudo cargar idioma: ${safe}`)
  const text = await resp.text()

  const t = {}
  for (const rawLine of text.split(/\r?\n/)) {
    const line = rawLine.trim()
    if (!line || line.startsWith('#')) continue
    const idx = line.indexOf('=')
    if (idx === -1) continue
    const key = line.slice(0, idx).trim()
    const value = line.slice(idx + 1).trim()
    if (key) t[key] = value
  }
  return t
}
