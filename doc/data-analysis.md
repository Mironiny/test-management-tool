# Datová analýza 

## Co zobrazovat uživateli?

* **Správu jednotlivých SUT**
	* Zobrazení statistik (kolik je dokončeno, odhadovaná doba do konce)

* **Správu Test Requirement**
	* Přidávání nových TR
	* Editace

* **Test Run**
	* Přirazení zodpovednosti za provedeni 1 .. N testerům
	* Zobrazit výsledky jednotlivých běhů
	* Zobrazení statistik (kolik je dokončeno, odhadovaná doba do konce)

* **Správu Test Suite**
	* Přidávání a editace

* **Správu Test Case**
	* Přidávání a editace


## Co je třeba mít uložené v DB?

* **System Under Test**

	Nejvyšší vrstva - definuje projekt či testovaný systém
	* Jméno projektu
	* Popis projektu - co se bude testovat
	* Popis jakým způsobem se bude testovat
	* HW vybavení
	* SW vybavení
	* Další potřebné prostředky pro testování - např. docker či virtuálka
	* Datum začátku testování
	* Manažerské informace jako odhadovaná doba testování, skutečná doba testo-
	  vání atd.

* **Test Requirement**

	Specifikace jednotlivých požadavků na SUT
	* Textová forma v podobě jednotlivých odrážek, které budou pokrývat Test Cases
	* Požadovaná kritéria pokrytí CFG -> možnost generovat Test Cases

* **Test Run**

	Definuje jedno spuštění daného Test Case/Suite
	* Výsledky běhu - Pass vs. Fail
	* Kde bude Run spouštěný - např. server, virtuální stroj, vzdalený stroj atd.
	* Čás spuštění, doba provádění běhu
	* Člověk, zodpovědný za spuštění

* **Test Executor**

	Definuje, kdo provádí dané Test Case/Suite
	* Člověk
	* Nástroj pro spouštění automatizovaných testů - např. Jenkins nebo
	  Selenium

* **Test Suite**

	Nadmnožina Test Case
	* Cíle
	* Verze
	* Dokumentace

* **Test Case**

	Definuje jeden testovací příklad
	* Test fixture
	* Popis 
	* V případě manuální testu návod na provedení testu
	* V případě automatizovaého testu - zdrojový kod
	* Datum vytvoření
	* Autor


