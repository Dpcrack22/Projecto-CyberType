const KEY = 'marveltype.session.v1'

export function loadSession() {
  try {
    const raw = localStorage.getItem(KEY)
    if (!raw) return null
    return JSON.parse(raw)
  } catch {
    return null
  }
}

export function saveSession(patch) {
  const prev = loadSession() || {}
  const next = { ...prev, ...patch }
  localStorage.setItem(KEY, JSON.stringify(next))
  return next
}

export function clearSession() {
  localStorage.removeItem(KEY)
}
