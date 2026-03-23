function pickN(difficulty) {
  if (difficulty === 'facil') return 3
  if (difficulty === 'medio') return 4
  if (difficulty === 'dificil') return 5
  return 3
}

function shuffle(arr) {
  for (let i = arr.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1))
    ;[arr[i], arr[j]] = [arr[j], arr[i]]
  }
  return arr
}

export async function loadSentences({ lang = 'es', difficulty = 'medio' }) {
  const safe = ['es', 'ca', 'en'].includes(lang) ? lang : 'es'
  const resp = await fetch(`/sentences${safe}.txt`, { cache: 'no-store' })
  if (!resp.ok) throw new Error('No se pudo cargar el archivo de frases')

  const text = (await resp.text()).replace(/\r/g, '')

  // Extract difficulty blocks even if the file has odd newlines/spaces.
  const blocks = []
  const re = /(facil|medio|dificil)\|([\s\S]*?)(?=(facil|medio|dificil)\||$)/gi
  let m
  while ((m = re.exec(text))) {
    blocks.push({
      dif: String(m[1]).toLowerCase().trim(),
      body: String(m[2] || ''),
    })
  }

  const target = String(difficulty).toLowerCase().trim()
  const block = blocks.find((b) => b.dif === target)
  if (!block) return []

  const cleaned = block.body.replace(/\n+/g, ' ').trim()
  const rawItems = cleaned
    .split(',')
    .map((s) => s.trim())
    .filter(Boolean)

  const parsed = rawItems.map((raw) => {
    const [frase, imagen] = raw.split('@@')
    return {
      frase: (frase || '').trim(),
      imagen: (imagen || '').trim(),
    }
  })

  const n = pickN(target)
  return shuffle(parsed).slice(0, n)
}
