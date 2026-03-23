import { backendFetch } from './backend'

export function getTranslations({ lang } = {}) {
  return backendFetch('api/translations.php', {
    method: 'POST',
    form: {
      lang,
    },
  })
}
