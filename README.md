# PHPReact Prototype mit Symfony 6

## Projektbeschreibung

Dieses Projekt ist ein Symfony 6-Prototyp, der die Machbarkeit von Swoole und ReactPHP innerhalb eines Symfony-Frameworks testet. Ziel ist es, verschiedene Ansätze für die Verarbeitung von Tasks in einer Redis-basierten Queue zu evaluieren und weiterzuentwickeln.

## Commands

### 1. `app:react-pubsub`
- Startet einen Consumer auf Basis von ReactPHP.
- Fragt die Redis-Liste ab, um Tasks aus der Task Queue zu verarbeiten.

### 2. `app:swoole-pubsub`
- Startet einen Consumer auf Basis von Swoole.
- Fragt die Redis-Liste ab, um Tasks aus der Task Queue zu verarbeiten.

### Parallelbetrieb
Beide Commands können unabhängig voneinander oder parallel genutzt werden. Durch das Starten mehrerer CLI-Commands ist es möglich, die Anzahl der gleichzeitig verarbeiteten Tasks zu skalieren.

## Task-Verwaltung

Die Verwaltung der Tasks erfolgt über die Klassen `Subscriber` und `Publisher`, die im Namespace `PubSub` bereitgestellt werden:

### Subscriber
- Wird von den Commands genutzt, um die Kanäle zu abonnieren.
- Stellt sicher, dass Nachrichten aus den abonnierten Kanälen verarbeitet werden.

### Publisher
- Wird verwendet, um neue Nachrichten in die Redis Queue (Liste) zu schreiben.
- Ermöglicht die Erzeugung neuer Tasks, die später von den Consumer-Commands abgearbeitet werden.

## Abhängigkeiten
- **ReactPHP**: Für asynchrone Event-Loop-basierte Verarbeitung.
- **Swoole**: Für Koroutinen-basierte Verarbeitung.
- **Redis**: Zum Speichern der Task Queue und als Kommunikationsmechanismus zwischen Publisher und Subscriber.

## Zukunftsperspektive
In der nächsten Entwicklungsphase sollen folgende Features implementiert werden:

1. **Tasks statt Messages**:
    - Ein Task enthält zusätzliche Metadaten und eindeutige IDs.
    - Ermöglicht die Kommunikation zwischen Backend und Frontend.

2. **Task-Verwaltung**:
    - Tasks sollen Statusinformationen enthalten (z. B. "in Bearbeitung", "abgeschlossen").
    - Die Consumer können Statusmeldungen oder Nachrichten im Task hinterlassen.
    - Das Frontend kann den Status eines Tasks jederzeit abfragen.

3. **Redis als zentrale Speicherung**:
    - Aufgaben werden vollständig in Redis gespeichert.
    - Ermöglicht schnelle Abfragen und effiziente Kommunikation.

## Nutzung
### Voraussetzungen
- PHP mit den Erweiterungen für **ReactPHP** und/oder **Swoole**.
- Ein Redis-Server muss laufen und erreichbar sein.

### Commands starten
1. React-Consumer:
   ```bash
   php bin/console app:react-pubsub
   ```

2. Swoole-Consumer:
   ```bash
   php bin/console app:swoole-pubsub
   ```

3. Beide parallel starten, um mehr Tasks gleichzeitig zu verarbeiten:
   ```bash
   php bin/console app:react-pubsub &
   php bin/console app:swoole-pubsub
   ```

## Fazit
Das Projekt bietet eine Grundlage, um verschiedene Ansätze für die Task-Verarbeitung in Symfony zu testen und weiterzuentwickeln. ReactPHP und Swoole können je nach Anwendungsfall flexibel eingesetzt werden, um die Effizienz und Skalierbarkeit zu erhöhen.