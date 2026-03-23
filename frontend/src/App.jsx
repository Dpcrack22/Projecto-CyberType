import { useState } from 'react'
import GameSandbox from './game/GameSandbox'
import AdminSandbox from './admin/AdminSandbox'
import './App.css'

function App() {
  const [section, setSection] = useState('game')

  return (
    <div style={{ padding: 16 }}>
      <h1>MarvelType (migración a React)</h1>

      <div style={{ display: 'flex', gap: 8, flexWrap: 'wrap', margin: '12px 0 16px' }}>
        <button onClick={() => setSection('game')} disabled={section === 'game'}>
          Juego
        </button>
        <button onClick={() => setSection('admin')} disabled={section === 'admin'}>
          Admin
        </button>
      </div>

      {section === 'game' ? <GameSandbox /> : <AdminSandbox />}
    </div>
  )
}

export default App
