# Post Statistics WordPress Plugin

## Plugin-Header
- Die Anfangskommentare geben WordPress grundlegende Informationen über das Plugin, wie Name, URI, Beschreibung, Version und Autor.

## Klassen-Definition `PostStat`
- Diese Klasse beinhaltet die Funktionalitäten des Plugins.

## Konstruktor `__construct`
- Im Konstruktor werden WordPress-Hooks registriert.
- `add_action('admin_menu', array($this, 'pluginSettingMenuEntry'))`: Fügt einen Menüeintrag in das WordPress-Admin-Menü hinzu.
- `add_action('admin_init', array($this, 'settings'))`: Initialisiert Plugin-Einstellungen im Admin-Bereich.
- `add_filter('the_content', array($this, 'outputStats'))`: Modifiziert den Inhalt von Beiträgen durch Anhängen von Statistiken.

## Funktion `settings`
- Definiert Einstellungsbereiche und -felder für das Plugin im Admin-Bereich.
- Hier werden Optionen wie Anzeigeort, Überschriftentext und verschiedene Checkboxen (z.B. für Wortzählung, Zeichenzählung) hinzugefügt.

## Funktion `outputStats`
- Diese Funktion modifiziert den Inhalt der Beiträge, um Statistiken wie Wortanzahl, Zeichenzahl, Lesedauer und Absatzanzahl anzuzeigen.
- Sie prüft, ob die entsprechenden Optionen aktiviert sind, und fügt dann die Statistiken zum Inhalt hinzu.

## Funktion `pluginSettingMenuEntry`
- Fügt eine Einstellungsseite für das Plugin zum WordPress-Admin-Menü hinzu.

## Funktion `pluginSettingHTML`
- Erzeugt das HTML für die Plugin-Einstellungsseite.

## Funktionen `locationHTML`, `headlineHTML`, `checkboxHTML`
- Diese Funktionen generieren das HTML für die verschiedenen Einstellungsfelder auf der Einstellungsseite.

## Funktion `sanitizeLocation`
- Eine Validierungsfunktion, die sicherstellt, dass der Anzeigeort entweder Anfang oder Ende des Beitrags ist.

## Instantiierung der Klasse `PostStat`
- Am Ende des Skripts wird ein Objekt der Klasse `PostStat` erstellt, was dazu führt, dass der Konstruktor aufgerufen wird und das Plugin aktiviert wird.