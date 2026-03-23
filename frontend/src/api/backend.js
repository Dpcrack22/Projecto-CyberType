export async function backendFetch(path, options = {}) {
  const {
    method = 'GET',
    headers = {},
    body,
    form,
    json,
    credentials = 'include',
  } = options

  const finalHeaders = { ...headers }
  let finalBody = body

  if (form) {
    finalHeaders['Content-Type'] = 'application/x-www-form-urlencoded'
    finalBody = new URLSearchParams(form)
  }

  if (json !== undefined) {
    finalHeaders['Content-Type'] = 'application/json'
    finalBody = JSON.stringify(json)
  }

  const normalizedPath = String(path || '').replace(/^\/+/, '')
  const response = await fetch(`/backend/${normalizedPath}`, {
    method,
    headers: finalHeaders,
    body: finalBody,
    credentials,
  })

  const contentType = response.headers.get('content-type') || ''
  const text = await response.text()

  if (!response.ok) {
    throw new Error(`HTTP ${response.status}: ${text.slice(0, 300)}`)
  }

  if (contentType.includes('application/json')) {
    return text ? JSON.parse(text) : null
  }

  return text
}
