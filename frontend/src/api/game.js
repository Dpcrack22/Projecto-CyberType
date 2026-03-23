import { backendFetch } from './backend'

export function getSentences({ difficulty, lang }) {
  return backendFetch('get_sentence.php', {
    method: 'POST',
    form: {
      difficulty,
      lang,
    },
  })
}
