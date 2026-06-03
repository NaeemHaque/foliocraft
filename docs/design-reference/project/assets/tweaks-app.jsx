// tweaks-app.jsx — wires the Tweaks panel to the portfolio's CSS variables.
const TWEAK_DEFAULTS = /*EDITMODE-BEGIN*/{
  "accent": "#e6926b",
  "displayFont": "Grotesk",
  "motion": "medium"
}/*EDITMODE-END*/;

const FONT_MAP = {
  Grotesk: '"Space Grotesk", "Sora", system-ui, sans-serif',
  Sora: '"Sora", "Space Grotesk", system-ui, sans-serif',
  Plex: '"IBM Plex Sans", system-ui, sans-serif'
};

function TweaksApp() {
  const [t, setTweak] = useTweaks(TWEAK_DEFAULTS);

  React.useEffect(() => {
    const root = document.documentElement;
    root.style.setProperty('--accent', t.accent);
  }, [t.accent]);

  React.useEffect(() => {
    document.documentElement.style.setProperty('--font-display', FONT_MAP[t.displayFont] || FONT_MAP.Grotesk);
  }, [t.displayFont]);

  React.useEffect(() => {
    document.documentElement.setAttribute('data-motion', t.motion);
  }, [t.motion]);

  return (
    <TweaksPanel title="Tweaks">
      <TweakSection label="Accent" />
      <TweakColor
        label="Highlight color"
        value={t.accent}
        options={['#4fbb87', '#58a6ff', '#e6926b', '#a78bfa', '#2dd4bf', '#e6c45c']}
        onChange={(v) => setTweak('accent', v)}
      />
      <TweakSection label="Typography" />
      <TweakRadio
        label="Display font"
        value={t.displayFont}
        options={['Grotesk', 'Sora', 'Plex']}
        onChange={(v) => setTweak('displayFont', v)}
      />
      <TweakSection label="Motion" />
      <TweakRadio
        label="Animation"
        value={t.motion}
        options={['subtle', 'medium', 'expressive']}
        onChange={(v) => setTweak('motion', v)}
      />
    </TweaksPanel>
  );
}

(function mountTweaks() {
  const el = document.getElementById('tweaks-root');
  if (el && window.ReactDOM && window.useTweaks) {
    ReactDOM.createRoot(el).render(<TweaksApp />);
  }
})();
