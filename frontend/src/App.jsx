import { useState } from 'react'
import IndexPage from './index/IndexPage'
import PlayPage from './play/PlayPage'

function App() {
  const [view, setView] = useState('index')

  if (view === 'index') {
    return (
      <IndexPage
        onStart={(cfg) => {
          void cfg
          setView('play')
        }}
      />
    )
  }

  return <PlayPage onBack={() => setView('index')} />
}

export default App
