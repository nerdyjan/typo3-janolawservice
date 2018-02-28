**Einbindungsanleitung TYPO3**

**Versionshinweis**

Bitte prüfen Sie ob Ihnen die Rechtstexte in der **janolaw Version 3**
zur Verfügung stehen. Dies ist der Fall, wenn die Texte im Jahr 2016
erstellt worden sind. Sollten Sie Zweifel haben, dann prüfen Sie nach
dem Login in Ihrem persönlichen Bereich `My
janolaw <https://www.janolaw.de/login.html>`__ in der Übersicht das
Datum der letzten Erstellung bzw. ob Sie den Menüpunkt "Stammdaten ändern"
haben. Mit einer einmaligen Neubeantwortung des gesamten Fragenkatalogs
erhalten Sie automatisch die aktuellste Version.

**Hinweis**

Bitte achten Sie auch darauf, welchen Service Sie erworben haben, ob es
sich hierbei um die deutsche bzw. mehrsprachige Version handelt und ob
darin nur das Impressum und die Datenschutzerklärung
(`Webseite <http://www.janolaw.de/internetrecht/firmen-webseiten/datenschutzerklaerung_impressum.html>`__)
enthalten sind bzw. auch die AGB, Widerrufsbelehrung und das
Muster-Widerrufsformular
(`Internetshop <http://www.janolaw.de/internetrecht/internetshop/abmahnschutz-internetshop.html>`__)

**1. Grundkonfiguration**

Rufen Sie nach der Installation das `janolaw
Plugin <https://typo3.org/extensions/repository/>`__ über den Punkt
Erweiterungen auf.

|image0|

Tragen Sie in die Maske Ihre **User-ID (Kundennummer)** und **Shop-ID**
ein, die Sie von janolaw erhalten haben. Sie finden diese in Ihrem
persönlichen Bereich `My janolaw <https://www.janolaw.de/login.html>`__
bzw. in der E-Mail, die Sie nach der Erstellung der Dokumente erhalten
haben. Bestätigen Sie die Angaben mit dem Speichern Button.

Sie können ein individuelles Aktualisierungsintervall für die Pufferung
der Texte setzten. Wenn Sie die neuste Version der Rechtstexte von
janolaw nutzen sollte die Übersicht wie unten stehend aussehen.

|image1|

**2. Webseiten**

Rufen Sie den Menüpunkt Seite auf und ergänzen dort die bestehenden
Seiten um die Rechtstexte von janolaw bzw. legen falls nötig neue Seiten
an.

|image2|

Um die Texte automatisiert in den Content der Webseiten einzubinden
rufen Sie die spezifische Seite aus und wählen in den Inhaltselementen
„\ *Plug-Ins*\ “ aus.

|image3|

Nach dem Öffnen der Content Seite wählen Sie unter dem Schiebereiter „\ *Allgemein*\ “ den Menüpunkt „\ *Ausgewähltes Plug-In*\ “ aus und rufen dort das janolaw Plugin auf.
Hier können Sie nun die bzw. das spezifische Dokument von janolaw der jeweiligen Webseite zuordnen bzw. falls vorhanden auch die jeweilige Sprachversion des Dokuments zuordnen. 

In der janolaw Version 3 (vgl. S. 1 Versionshinweis der Einbindungsanleitung) ist es möglich über die Auswahl „\ *PDF Link*\ “ im Frontend der Webseiten die PDF-Version des jeweiligen Dokuments in Form eines Downloadlink unterhalb oder oberhalb der Rechtstexte bzw. nur den Link anzeigen zu lassen.

Bestätigen Sie bitte die Eingabe mit dem „\ *Speichern Button*\ “!


|image5|

**3. Konfiguration**

Alternativ können Sie Texte auch über TypoScript in Ihr Template
integrieren.

TypoScript Beispiel

    | lib.pdflink = USER
    | lib.pdflink {
    | userFunc = TYPO3\\CMS\\Extbase\\Core\\Bootstrap->run
    | extensionName = Janolawservice
    | pluginName = Showjanolawservice
    | vendorName = Janolaw
    | settings.janolawservice.language=de
    | settings.janolawservice.type=terms
    | settings.janolawservice.pdflink=only\_pdf\_link
    | settings.janolawservice.userid=123
    | settings.janolawservice.shopid=123
    | }

Mögliche Werte für settings.janolowservice:

-  language: en\|gb\|fr

-  type:
   terms\|legaldetails\|revocation\|datasecurity\|model-withdrawal-form

-  pdflink: no\_pdf\|pdf\_top\|pdf\_bottom\|only\_pdf\_link (no\_pdf ist
   default)

-  userid: enthält die UserID falls dies abweicht von der Zentralen Einstellung

-  shopid: enthält die UserID falls dies abweicht von der Zentralen Einstellung

**Multisite**

Wenn Sie in einer TYPO3 Installation mehrere Seiten verwenden, die unterschiedliche Shop/UserIds bei janolaw haben, so können Sie diese über TypoScript im jeweiligen Seitentemplate aussteuern.
Hierzu tragen Sie ein:
plugin.tx_janolawservice_showjanolawservice.settings.janolawservice.shopid=123
plugin.tx_janolawservice_showjanolawservice.settings.janolawservice.userid=123

**Hinweis**

Bitte nehmen Sie eventuelle Änderungen an den janolaw Dokumenten
ausschließlich auf www.janolaw.de vor. Dazu müssen Sie sich in den
Bereich `My janolaw <https://www.janolaw.de/login.html>`__ einloggen und
dort die Dokumente ggf. neu erstellen.

**Wichtig**

Bitte prüfen Sie ob die Rechtstexte nach Aktivierung des Plugins auf den Webseiten erscheinen.
Bei Verwendung des AGB Hosting-Service für einen Internetshop prüfen Sie bitte im Rahmen eines Testkaufs ob Sie die Dokumente, AGB, Widerrufsbelehrung, Muster-Widerrufsformular und ab **Mai 2018** die Datenschutzerklärung in die **E-Mail Auftragsbestätigung** händisch korrekt eingebunden haben oder ob Sie die Dokumente spätestens mit dem Warenversand dem Kunden zuschicken.
Wenn die Einbindung korrekt erfolgt ist, werden die Dokumente über die Schnittstelle synchronisiert und automatisch auf den jeweiligen Webseiten aktualisiert. Bitte beachten Sie, dass wenn Sie einen Internetshop haben Sie bei Änderungen der Rechtstexte die Dokumente in der E-Mail Auftragsbestätigung selbst austauschen müssen.

**Muster-Widerrufsformular**

Das Muster-Widerrufsformular muss per E-Mail oder spätestens mit dem
Warenversand zuschickt werden. Zusätzlich muss das Widerrufsformular als
weiterer Menüpunkt / Link in Ihren Internetshop neben den schon
bestehenden für AGB, Impressum, Datenschutzerklärung und
Widerufsbelehrung angelegt werden. `Informationen zum
Muster-Widerrufsformular <http://www.janolaw.de/docs/muster-widerrufsformular.doc>`__

**Online Streitschlichtungsvorlage (OS-Plattform)**

Nach der europäischen ODR-Verordnung (Verordnung über die außergerichtliche Online-Beilegung verbraucherrechtlicher Streitigkeiten) müssen Unternehmer, die an Verbraucher verkaufen seit dem 9. Januar 2016 auf ihren Webshops einen **aktiven Link** auf die OS-Plattform (`https://ec.europa.eu/consumers/odr/ <https://ec.europa.eu/consumers/odr/>`__) setzen und **ihre E-Mail Adresse angeben**.

**WICHTIG:**

Sie sollten den Link zur Plattform weder unter das Impressum noch in die
AGB einfügen, da er dort als "versteckt" gelten könnte.

Hier unser Textvorschlag:

Die EU-Kommission stellt eine Plattform für außergerichtliche
Streitschlichtung bereit. Verbrauchern gibt dies die Möglichkeit,
Streitigkeiten im Zusammenhang mit ihrer Online-Bestellung zunächst
außergerichtlich zu klären. Die Streitbeilegungs-Plattform finden Sie
hier: `http://ec.europa.eu/consumers/odr/ <http://ec.europa.eu/consumers/odr/>`__ 

Unsere E-Mail für Verbraucherbeschwerden lautet: ......@...... 

.. |image0| image:: /Images/image1.png
   :width: 6.30000in
   :height: 3.72422in
.. |image1| image:: /Images/image2.png
   :width: 5.77351in
   :height: 5.66981in
.. |image2| image:: /Images/image3.png
   :width: 4.13365in
   :height: 3.00943in
.. |image3| image:: /Images/image4.png
   :width: 6.30000in
   :height: 1.84150in
.. |image4| image:: /Images/image5.png
   :width: 3.85833in
   :height: 4.28333in
.. |image5| image:: /Images/image6.png
   :width: 3.66042in
   :height: 5.00000in
