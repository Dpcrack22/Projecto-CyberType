import { backendFetch } from './backend'

export function listAdminSentences({ lang } = {}) {
  return backendFetch('admin/api/list_sentences.php', {
    method: 'POST',
    form: {
      lang,
    },
  })
}

export function deleteAdminSentence({ dificultad, frase, lang } = {}) {
  return backendFetch('admin/api/delete_sentence.php', {
    method: 'POST',
    form: {
      dificultad,
      frase,
      lang,
    },
  })
}
