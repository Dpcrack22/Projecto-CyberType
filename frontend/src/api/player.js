import { backendFetch } from './backend'

export function getPlayerState() {
  return backendFetch('api/player_state.php', {
    method: 'POST',
  })
}

export function startGame({ playerName, difficulty, permadeathCheckbox, lang }) {
  return backendFetch('api/start_game.php', {
    method: 'POST',
    form: {
      playerName,
      difficulty,
      permadeathCheckbox,
      lang,
    },
  })
}
