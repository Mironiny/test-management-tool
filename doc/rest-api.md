# Rest API pro Test-management-tool
(Verze 1.0)

## Přehled

Následující dokument poskytne dokumentaci k API k test-management-toolu, které je
postavené principu RESTu, mluvíme tedy o REST API.

V následujících částech bude dokumentován serializační formát, autentizace k API a dále jednotlivé
zdroje (resources), s nimiž jde pomocí API zacházet.

Podsekce:

[Test suite](#test-suites)

[Test case](#test-cases)

[Project](#projects)

[Requirement](#requirements)

[Test set](#test-sets)

[Test run](#test-runs)

## Serializační formát

Aplikace podporuje příjem požadavků ve formátu JSON. Aplikace odpovídá rovněž ve
formátu JSON. Jiné formáty nebudou akceptovány.

## Adresace

Standartní adresace api je *host/api/v**X**/resource*, kde x je číslo verze daného api.

## Autentizace

Autentizace je implenetovaná pomocí tzn. API tokenu, který je předáván při každém
requestu do aplikace. Token je předáván v **HTTP** hlavičce v poli Authorization
pomocí Bearer Token. Viz RFC 6750.

API token je generován každému uživateli při registraci a skládá se z 60 alfanumerických znaků. Uživatel ho poté nalezne na stránce *User settings*.

### Příklad HTTP hlavičky

    GET /api/v1/testcases/1 HTTP/1.1
    Host: localhost:8000
    Authorization: Bearer faQIfZxazJK5tYx40bbsEAG7G2Ab7zzlkLQ1PDp9BRF2I3RHRDkM7VSYP3Rj
    Cache-Control: no-cache

V případě POST a PUT je potřeba zahrnout i další pole:

    Content-Type: application/json

### Příklad cURL volání
    curl --request GET \
    --url http://localhost:8000/api/v1/testcases/1 \
    --header 'authorization: Bearer faQIfZxazJK5tYx40bbsEAG7G2Ab7zzlkLQ1PDp9BRF2I3RHRDkM7VSYP3Rj' \
    --header 'cache-control: no-cache' \

V případě nepovedené autentizace, je uživateli odeslána odpověď s návratovým HTTP
kódem **401** a obsahem:

```json
{
    "error" : "Unauthenticated."
}
```


## Test suites

Method | URI
------------ | -------------
GET | /api/v1/testsuites
GET | /api/v1/testsuites/{suiteId}
POST | /api/v1/testsuites
PUT | /api/v1/testsuites/{suiteId}
DELETE | /api/v1/testsuites/{suiteId}

### GET /api/v1/testsuites

#### Popis
 Vrátí všechny testovací sady.

#### Odpovědi

1) **Úspěch**: návratový kód: 200

Obsah:

```json
{
    "TestSuite_id": "number",
    "Name": "string",
    "TestSuiteGoals": "string",
    "TestSuiteVersion": "string",
    "TestSuiteDocumentation": "string",
    "href": "string"
}
```

---

### GET /api/v1/testsuites/{id}

#### Popis
Vrátí vybranou testovací sadu

#### Parametry
id - identifikator testovací sady

#### Odpovědi

1) **Úspěch** - návratový kód: 200

Obsah:

```json
{
    "TestSuite_id": "number",
    "Name": "string",
    "TestSuiteGoals": "string",
    "TestSuiteVersion": "string",
    "TestSuiteDocumentation": "string",
    "href": "string"
}
```

---

### POST /api/v1/testsuites

#### Popis
Uloží testovací sadu do aplikace

#### Vstup

```json
{
    "*Name*": "string",
    "TestSuiteGoals": "string",
    "TestSuiteVersion": "string",
    "TestSuiteDocumentation": "string"
}
```
Poznámka: Atributy označené * jsou povinné

#### Odpovědi

1) **Úspěch** - návratový kód: 201

Obsah:

```json
{
    "TestSuite_id": "number",
    "Name": "string",
    "TestSuiteGoals": "string",
    "TestSuiteVersion": "string",
    "TestSuiteDocumentation": "string",
    "href": "string"
}
```

2) **Neúspěch** - návratový kód: 400
Popis: V případě nezadaného jména, nebo v případě, kdy jméno překročí
maximální hranici 45 znaků

Obsah:

```json
{
    "error": "Test suite name error"
}
```

---

### PUT /api/v1/testsuites/{id}

#### Popis
 Edituje existující testovací sadu

#### Vstup

```json
{
    "Name": "string",
    "TestSuiteGoals": "string",
    "TestSuiteVersion": "string",
    "TestSuiteDocumentation": "string"
}
```

#### Odpovědi

1) **Úspěch** - návratový kód: 201

Obsah:

```json
{
    "TestSuite_id": "number",
    "Name": "string",
    "TestSuiteGoals": "string",
    "TestSuiteVersion": "string",
    "TestSuiteDocumentation": "string",
    "href": "string"
}
```

2) **Neúspěch** - návratový kód: 404

Popis: V případě nenalezení testovací sady

Obsah:

```json
{
    "error": "Testsuite not foundr"
}
```

---

## DELETE /api/v1/testsuites/{id}

#### Popis
Archvivuje danou testovací sadu

#### Odpovědi

1) **Úspěch** - návratový kód: 200

Obsah:

```json
{
    "success": "Deleted"
}
```

2) **Neúspěch** - návratový kód: 404

Popis: V případě nenalezení testovací sady

Obsah:

```json
{
    "error": "Testsuite not foundr"
}
```



## Test cases


Method | URI
------------ | -------------
GET | /api/v1/testcases
GET | /api/v1/testcases/{caseId}
POST | /api/v1/testcases
PUT | /api/v1/testcases/{caseId}
DELETE | /api/v1/testcases/{caseId}


### GET /api/v1/testcases

#### Popis
Vrátí všechny testovací případy

#### Odpovědi

1) **Úspěch**: návratový kód: 200

Obsah:

```json
{
    "TestCase_id": "number",
    "TestSuite_id": "number",
    "Name": "string",
    "IsManual": 0,
    "TestCasePrefixes": "string",
    "TestSteps": "string",
    "ExpectedResult": "string",
    "TestCaseSuffixes": "string",
    "SourceCode": "string",
    "TestCaseDescription": "string",
    "Note": "string",
    "href": "string"
}
```

---

### GET /api/v1/testcases/{id}

#### Popis
Vrátí vybraný testovací případ

#### Parametry
id - identifikator testovacího případu

#### Odpovědi

1) **Úspěch** - návratový kód: 200

Obsah:

```json
{
    "TestCase_id": "number",
    "TestSuite_id": "number",
    "Name": "string",
    "IsManual": 0,
    "TestCasePrefixes": "string",
    "TestSteps": "string",
    "ExpectedResult": "string",
    "TestCaseSuffixes": "string",
    "SourceCode": "string",
    "TestCaseDescription": "string",
    "Note": "string",
    "href": "string"
}
```

---

### GET /api/v1/testsuites/{idSuite}/testcases

#### Popis
Vrátí všechny testovací případy pro danou testovací sadu

#### Parametry
id - identifikator testovací sady

#### Odpovědi

1) **Úspěch** - návratový kód: 200

Obsah:

```json
{
    "TestCase_id": "number",
    "TestSuite_id": "number",
    "Name": "string",
    "IsManual": 0,
    "TestCasePrefixes": "string",
    "TestSteps": "string",
    "ExpectedResult": "string",
    "TestCaseSuffixes": "string",
    "SourceCode": "string",
    "TestCaseDescription": "string",
    "Note": "string",
    "href": "string"
}
```

2) **Neúspěch** - návratový kód: 404

Popis: testovací sada nebyla nalezena

Obsah:

```json
{
    "error": "Testsuit not found."
}
```
---


### POST /api/v1/testcases

#### Popis
Uloží testovací případ do aplikace

#### Vstup

```json
{
    "*TestSuite_id*": "number",
    "*Name*": "string",
    "IsManual": 0,
    "TestCasePrefixes": "string",
    "TestSteps": "string",
    "ExpectedResult": "string",
    "TestCaseSuffixes": "string",
    "TestCaseDescription": "string",
    "Note": "string"
}
```
Poznámka: Atributy označené * jsou povinné

#### Odpovědi

1) **Úspěch** - návratový kód: 201

Obsah:

```json
{
    "TestCase_id": "number",
    "TestSuite_id": "number",
    "Name": "string",
    "IsManual": 0,
    "TestCasePrefixes": "string",
    "TestSteps": "string",
    "ExpectedResult": "string",
    "TestCaseSuffixes": "string",
    "SourceCode": "string",
    "TestCaseDescription": "string",
    "Note": "string",
    "href": "string"
}
```

2) **Neúspěch** - návratový kód: 400

Popis: V případě zadání neexistující TestSuite_id

Obsah:

```json
{
    "error": "Test suite doesn't exists"
}
```

3) **Neúspěch** - návratový kód: 400

Popis: V případě nezadání atributu Name či překročení maximální povolené velikosti 45 znaků

Obsah:

```json
{
    "error": "Test case name error"
}
```

### PUT /api/v1/testcases/{id}

#### Popis
Edituje existující testovací případ

#### Parametry
id - identifikator testovacího případu

#### Vstup

```json
{
    "TestSuite_id": "number",
    "Name": "string",
    "IsManual": 0,
    "TestCasePrefixes": "string",
    "TestSteps": "string",
    "ExpectedResult": "string",
    "TestCaseSuffixes": "string",
    "TestCaseDescription": "string",
    "Note": "string"
}
```

#### Odpovědi

1) **Úspěch** - návratový kód: 201

Obsah:

```json
{
    "TestSuite_id": "number",
    "Name": "string",
    "TestSuiteGoals": "string",
    "TestSuiteVersion": "string",
    "TestSuiteDocumentation": "string",
    "href": "string"
}
```

2) **Neúspěch** - návratový kód: 400

Popis: V případě nenalezení testovacího případu k editaci

Obsah:

```json
{
    "error": "TestCase not found"
}
```

---

### DELETE /api/v1/testcase/{id}

#### Popis
Archvivuje daný testovací případ

#### Odpovědi

1) **Úspěch** - návratový kód: 200

Obsah:

```json
{
    "success": "Deleted"
}
```

2) **Neúspěch** - návratový kód: 404

Popis: V případě nenalezení testovací sady

Obsah:

```json
{
    "error": "Testcase not found"
}
```



## Projects

Method | URI
------------ | -------------
GET | /api/v1/projects
GET | /api/v1/projects/{projectId}
POST | /api/v1/projects
PUT | /api/v1/projects/{projectId}
DELETE | /api/v1/projects/{projectId}

### GET /api/v1/projects

#### Popis
Vrátí všechny projekty přiřazené danému uživateli

#### Odpovědi

1) **Úspěch**: návratový kód: 200

Obsah:

```json

{
    "SUT_id": "number",
    "Name": "string",
    "ActiveDateFrom": "string",
    "ActiveDateTo": "string",
    "LastUpdate": "string",
    "ProjectDescription": "string",
    "TestingDescription": "string",
    "HwRequirements": "string",
    "SwRequirements": "string",
    "TestEstimatedDate": "string",
    "Note": "string",
    "Role" : "string"
}

```

---

### GET /api/v1/projects/{id}

#### Popis
Vrátí daný projekt uživateli

#### Parametry
id - identifikátor projektu

#### Odpovědi

1) **Úspěch**: návratový kód: 200

Obsah:

```json

{
    "SUT_id": "number",
    "Name": "string",
    "ActiveDateFrom": "string",
    "ActiveDateTo": "string",
    "LastUpdate": "string",
    "ProjectDescription": "string",
    "TestingDescription": "string",
    "HwRequirements": "string",
    "SwRequirements": "string",
    "TestEstimatedDate": "string",
    "Note": "string",
    "Role" : "string"
}

```

2) **Neúspěch** - návratový kód: 404

Popis: projekt nebyl nalezen

Obsah:

```json

{
    "error": "Project not found"
}

```

3) **Neúspěch** - návratový kód: 400

Popis: uživatel nemá práva k projektu

Obsah:

```json

{
    "error": "No rights to project"
}

```

---

### POST /api/v1/projects

#### Popis
Uloží testovací případ do aplikace

#### Vstup

```json
{
    "*Name*": "string",
    "ActiveDateFrom": "string",
    "ActiveDateTo": "string",
    "LastUpdate": "string",
    "ProjectDescription": "string",
    "TestingDescription": "string",
    "HwRequirements": "string",
    "SwRequirements": "string",
    "TestEstimatedDate": "string",
    "Note": "string",
}
```
Poznámka: Atributy označené * jsou povinné

#### Odpovědi

1) **Úspěch** - návratový kód: 201

Obsah:

```json
{
    "SUT_id": "number",
    "Name": "string",
    "ActiveDateFrom": "string",
    "ActiveDateTo": "string",
    "LastUpdate": "string",
    "ProjectDescription": "string",
    "TestingDescription": "string",
    "HwRequirements": "string",
    "SwRequirements": "string",
    "TestEstimatedDate": "string",
    "Note": "string",
}
```

2) **Neúspěch** - návratový kód: 400

Popis: V případě nezadání atributu Name či překročení maximální povolené velikosti 45 znaků

Obsah:

```json
{
    "error": "SUT name error"
}
```

### PUT /api/v1/projects/{id}

#### Popis
Edituje existující existujici projekt

#### Parametry
id - identifikator projektu

#### Vstup

```json
{
    "Name": "string",
    "ActiveDateFrom": "string",
    "ActiveDateTo": "string",
    "LastUpdate": "string",
    "ProjectDescription": "string",
    "TestingDescription": "string",
    "HwRequirements": "string",
    "SwRequirements": "string",
    "TestEstimatedDate": "string",
    "Note": "string",
}

```

#### Odpovědi

1) **Úspěch** - návratový kód: 201

Obsah:

```json
{
    "SUT_id": "number",
    "Name": "string",
    "ActiveDateFrom": "string",
    "ActiveDateTo": "string",
    "LastUpdate": "string",
    "ProjectDescription": "string",
    "TestingDescription": "string",
    "HwRequirements": "string",
    "SwRequirements": "string",
    "TestEstimatedDate": "string",
    "Note": "string",
}
```

2) **Neúspěch** - návratový kód: 404

Popis: V případě nenalezení testovacího případu k editaci

Obsah:

```json
{
    "error": "SUT not found"
}
```

---


### DELETE /api/v1/projects/{id}

### Popis
Archvivuje daný projekt

#### Parametry
id - identifikator projektu

#### Odpovědi

1) **Úspěch** - návratový kód: 200

Obsah:

```json
{
    "success": "Deleted"
}
```

2) **Neúspěch** - návratový kód: 404

Popis: V případě nenalezení testovací sady

Obsah:

```json
{
    "error": "Project not found"
}
```

3) **Neúspěch** - návratový kód: 400

Popis: uživatel nemá práva k projektu

Obsah:

```json
{
    "error": "No rights to project"
}

```


### Requirements


Method | URI
------------ | -------------
GET | /api/v1/projects/{projectId}/requirements
GET | /api/v1/projects/{projectId}/requirements/{requirementId}
POST | /api/v1/projects/{projectId}/requirements
PUT | /api/v1/projects/{projectId}/requirements/{requirementId}
DELETE | /api/v1/projects/{projectId}/requirements/{requirementId}


### GET /api/v1/projects/{projectId}/requirements

#### Popis
Zobrazí všechny požadavky k danému projektu včetně testovacích případů, které daný
požadavek pokrývají.

#### Odpovědi

1) **Úspěch**: návratový kód: 200

Obsah:

```json

{
    "TestRequirement_id": "number",
    "SUT_id": "number",
    "Name": "string",
    "CoverageCriteria": "string",
    "RequirementDescription": "string",
    "TestCase": [
        {
            "TestCase_id": "number",
            "Name": "string"
        }
    ]
}

```

2) **Neúspěch**: návratový kód: 404

Popis: Projekt nenalezen

Obsah:
```json

{
    "error": "Project not found"
}

```

3) **Neúspěch**: návratový kód: 400

Popis: Uživatel nemá práva k projektu

Obsah:
```json

{
    "error": "Not rights to project"
}

```
---

### GET /api/v1/projects/{projectId}/requirements/{requirementId}

#### Popis
Zobrazí všechny požadavky k danému projektu včetně testovacích případů, které daný
požadavek pokrývají.

#### Odpovědi

1) **Úspěch**: návratový kód: 200

Obsah:

```json

{
    "TestRequirement_id": "number",
    "SUT_id": "number",
    "Name": "string",
    "CoverageCriteria": "string",
    "RequirementDescription": "string",
    "TestCase": [
        {
            "TestCase_id": "number",
            "Name": "string"
        }
    ]
}

```

2) **Neúspěch**: návratový kód: 404

Popis: Projekt nenalezen

Obsah:
```json

{
    "error": "Project not found"
}

```

3) **Neúspěch**: návratový kód: 400

Popis: Uživatel nemá práva k projektu

Obsah:
```json

{
    "error": "Not rights to project"
}

```

4) **Neúspěch**: návratový kód: 400

Popis: Neexistující požadavek

Obsah:
```json

{
    "error": "No requirement found"
}

```
---

### POST /api/v1/projects/{projectId}/requirements

#### Popis
Uloží požadavek

#### Vstup

```json

{
    "*Name*": "string",
    "CoverageCriteria": "string",
    "RequirementDescription": "string"
}
```
Poznámka: Atributy označené * jsou povinné

#### Odpovědi

1) **Úspěch**: návratový kód: 201

Obsah:

```json

{
    "TestRequirement_id": "number",
    "Name": "string",
    "SUT_id": "number",
    "CoverageCriteria": "string",
    "RequirementDescription": "string",
    "LastUpdate": "string",
    "ActiveDateFrom": "string"

}

```

2) **Neúspěch**: návratový kód: 404

Popis: Projekt nenalezen

Obsah:
```json

{
    "error": "Project not found"
}

```

3) **Neúspěch**: návratový kód: 400

Popis: Uživatel nemá práva k projektu

Obsah:
```json

{
    "error": "Not rights to project"
}

```

4) **Neúspěch** - návratový kód: 400

Popis: V případě nezadání atributu Name či překročení maximální povolené velikosti 45 znaků

Obsah:

```json
{
    "error": "Requirement name error"
}
```

---

### PUT /api/v1/projects/{projectId}/requirements/{requirementId}

#### Popis
Uloží požadavek

#### Vstup

```json

{
    "Name": "string",
    "CoverageCriteria": "string",
    "RequirementDescription": "string"
}
```
#### Odpovědi

1) **Úspěch**: návratový kód: 201

Obsah:

```json

{
    "TestRequirement_id": "number",
    "Name": "string",
    "SUT_id": "number",
    "CoverageCriteria": "string",
    "RequirementDescription": "string",
    "LastUpdate": "string",
    "ActiveDateFrom": "string"

}

```

2) **Neúspěch**: návratový kód: 404

Popis: Projekt nenalezen

Obsah:
```json

{
    "error": "Project not found"
}

```

3) **Neúspěch**: návratový kód: 400

Popis: Uživatel nemá práva k projektu

Obsah:
```json

{
    "error": "Not rights to project"
}

```

4) **Neúspěch**: návratový kód: 400

Popis: Neexistující požadavek

Obsah:
```json

{
    "error": "No requirement found"
}

```

---

### DELETE /api/v1/projects/{projectId}/requirements/

#### Popis
Archivuje daný požadavek.

#### Odpovědi

1) **Úspěch**: návratový kód: 200

Obsah:

```json

{
    "success": "Deleted"
}

```

2) **Neúspěch**: návratový kód: 404

Popis: Projekt nenalezen

Obsah:
```json

{
    "error": "Project not found"
}

```

3) **Neúspěch**: návratový kód: 400

Popis: Uživatel nemá práva k projektu

Obsah:
```json

{
    "error": "Not rights to project"
}

```

4) **Neúspěch**: návratový kód: 400

Popis: Neexistující požadavek

Obsah:
```json

{
    "error": "No requirement found"
}

```
---


## Test sets

Method | URI
------------ | -------------
GET | /api/v1/projects/{projectId}/testsets
GET | /api/v1/projects/{projectId}/testsets/{setId}
POST | /api/v1/projects/{projectId}/testsets
PUT | /api/v1/projects/{projectId}/testsets/{setId}
DELETE | /api/v1/projects/{projectId}/testsets/{setId}


### GET /api/v1/projects/{projectId}/testsets

#### Popis
Zobrazí všechny aktivní testovací sety. Včetně testovacích případů, které jsou v
daném setu zahrnuty.

#### Odpovědi

1) **Úspěch**: návratový kód: 200

Obsah:

```json

{
    "TestSet_id": "number",
    "SUT_id": "number",
    "Name": "string",
    "Author": "string",
    "TestSetDescription": "string",
    "TestCase": [
      {
        "TestCase_id": "number",
        "Name": "string"
      }
  ]
}

```

2) **Neúspěch**: návratový kód: 404

Popis: Projekt nenalezen

Obsah:
```json

{
    "error": "Project not found"
}

```

3) **Neúspěch**: návratový kód: 400

Popis: Uživatel nemá práva k projektu

Obsah:
```json

{
    "error": "Not rights to project"
}

```
---


### GET /api/v1/projects/{projectId}/testsets/{setId}



#### Popis
Zobrazí vybraný testovací set včetně

#### Odpovědi

1) **Úspěch**: návratový kód: 200

Obsah:

```json

{
    "TestSet_id": "number",
    "SUT_id": "number",
    "Name": "string",
    "Author": "string",
    "TestSetDescription": "string",
    "TestCase": [
      {
        "TestCase_id": "number",
        "Name": "string"
      }
  ]
}

```

2) **Neúspěch**: návratový kód: 404

Popis: Projekt nenalezen

Obsah:
```json

{
    "error": "Project not found"
}

```

3) **Neúspěch**: návratový kód: 400

Popis: Uživatel nemá práva k projektu

Obsah:
```json

{
    "error": "Not rights to project"
}

```
---

4) **Neúspěch**: návratový kód: 404

Popis: Testset nenalezen

Obsah:
```json

{
    "error": "Not existing set"
}

```
---


### POST /api/v1/projects/{projectId}/testsets

#### Popis
Vytvoří testovací set a naplni ho testovacimi pripady.

#### Vstup

```json

{
    "*Name*": "string",
    "Author": "string",
    "TestSetDescription": "string",
    "*TestCases*": ["number"]
}
```
Poznámka: atributy označené hvězdičkou jsou povinné

#### Příklad

```json
{
    "Name": "Validace",
    "Author": "Admin",
    "TestSetDescription": "popis",
    "TestCases": [1, 2, 5, 10]
}
```

#### Odpovědi

1) **Úspěch**: návratový kód: 200

Obsah:

```json

{
    "TestSet_id": "number",
    "SUT_id": "number",
    "Name": "string",
    "Author": "string",
    "TestSetDescription": "string",
    "TestCase": [
      {
        "TestCase_id": "number",
        "Name": "string"
      }
  ]
}
```

2) **Neúspěch**: návratový kód: 404

Popis: Projekt nenalezen

Obsah:
```json

{
    "error": "Project not found"
}

```

3) **Neúspěch**: návratový kód: 400

Popis: Uživatel nemá práva k projektu

Obsah:
```json

{
    "error": "Not rights to project"
}

```

4) **Neúspěch**: návratový kód: 404

Popis: V případě nezadání atributu Name či překročení maximální povolené velikosti 45 znaků

Obsah:
```json

{
    "error": "Set name error"
}

```

---

### PUT /api/v1/projects/{projectId}/testsets/{setId}

#### Popis
Aktualizuje testset

#### Vstup

```json

{
    "Name": "string",
    "Author": "string",
    "TestSetDescription": "string"
}
```
### Odpovědi

1) **Úspěch**: návratový kód: 200

Obsah:

```json

{
    "TestSet_id": "number",
    "SUT_id": "number",
    "Name": "string",
    "Author": "string",
    "TestSetDescription": "string",
    "TestCase": [
      {
        "TestCase_id": "number",
        "Name": "string"
      }
  ]
}
```

2) **Neúspěch**: návratový kód: 404

Popis: Projekt nenalezen

Obsah:
```json

{
    "error": "Project not found"
}

```

3) **Neúspěch**: návratový kód: 400

Popis: Uživatel nemá práva k projektu

Obsah:
```json

{
    "error": "Not rights to project"
}

```

4) **Neúspěch**: návratový kód: 404

Popis: Testset nenalezen

Obsah:
```json

{
    "error": "Not existing set"
}

```
---

### DELETE /api/v1/projects/{projectId}/testsets/{setId}

#### Popis
Archivuje testset

1) **Úspěch**: návratový kód: 200

Obsah:

```json

```json
{
    "success": "Deleted"
}
```

2) **Neúspěch**: návratový kód: 404

Popis: Projekt nenalezen

Obsah:
```json

{
    "error": "Project not found"
}

```

3) **Neúspěch**: návratový kód: 400

Popis: Uživatel nemá práva k projektu

Obsah:
```json

{
    "error": "Not rights to project"
}

```

4) **Neúspěch**: návratový kód: 404

Popis: Testset nenalezen

Obsah:
```json

{
    "error": "Not existing set"
}

```

---


## Test runs

Method | URI
------------ | -------------
GET | /api/v1/projects/{projectId}/testsets/{setId}/testruns
GET | /api/v1/projects/{projectId}/testsets/{setId}/testruns/{runId}
POST | /api/v1/projects/{projectId}/testsets/{setId}/testruns
PUT | /api/v1/projects/{projectId}/testsets/{setId}/testruns/{runId}
DELETE | api/v1/projects/{projectId}/testsets/{setId}/testruns/{runId}
PUT | /api/v1/projects/{projectId}/testsets/{setId}/testruns/{runId}/testcase/{testCaseID}


### GET /api/v1/projects/{projectId}/testsets/{setId}/testruns


#### Popis
Zobrazí všechny test run náležící dané test set.

#### Odpovědi

1) **Úspěch**: návratový kód: 200

Obsah:

```json

{
    "TestRun_id": "number",
    "TestSet_id": "number",
    "Status": "string",
    "TestCase": [
        {
            "TestCase_id": "number",
            "Name": "string",
            "Status": "string"
        }
    ]
}

```

2) **Neúspěch**: návratový kód: 404

Popis: Projekt nenalezen

Obsah:
```json

{
    "error": "Project not found"
}

```

3) **Neúspěch**: návratový kód: 400

Popis: Uživatel nemá práva k projektu

Obsah:
```json

{
    "error": "Not rights to project"
}

```

4) **Neúspěch**: návratový kód: 404

Popis: Test set nenalezen

Obsah:
```json

{
    "error": "Testset don't exist"
}

```
---

### GET /api/v1/projects/{projectId}/testsets/{setId}/testruns/{runId}


#### Popis
Zobrazí daný test run.

#### Odpovědi

1) **Úspěch**: návratový kód: 200

Obsah:

```json

{
    "TestRun_id": "number",
    "TestSet_id": "number",
    "Status": "string",
    "TestCase": [
        {
            "TestCase_id": "number",
            "Name": "string",
            "Status": "string"
        }
    ]
}

```

2) **Neúspěch**: návratový kód: 404

Popis: Projekt nenalezen

Obsah:
```json

{
    "error": "Project not found"
}

```

3) **Neúspěch**: návratový kód: 400

Popis: Uživatel nemá práva k projektu

Obsah:
```json

{
    "error": "Not rights to project"
}

```

4) **Neúspěch**: návratový kód: 404

Popis: Test set nenalezen

Obsah:
```json

{
    "error": "Testset don't exist"
}

```
---

### POST /api/v1/projects/{projectId}/testsets/{setId}/testruns


#### Popis
Zobrazí daný test run

#### Vstup
Žádný vstup není zpracován. Pouhé zaslaní POST se správnými parametry v URL vytvoří
test run

#### Odpovědi

1) **Úspěch**: návratový kód: 200

Obsah:

```json

{
    "TestRun_id": "number",
    "TestSet_id": "number",
    "Status": "string",
    "TestCase": [
        {
            "TestCase_id": "number",
            "Name": "string"
        }
    ]
}

```
Poznámka: pro status jsou povolené hodnoty: "Running", "Finished" a "Archived".

2) **Neúspěch**: návratový kód: 404

Popis: Projekt nenalezen

Obsah:
```json

{
    "error": "Project not found"
}

```

3) **Neúspěch**: návratový kód: 400

Popis: Uživatel nemá práva k projektu

Obsah:
```json

{
    "error": "Not rights to project"
}

```

4) **Neúspěch**: návratový kód: 404

Popis: Test set nenalezen

Obsah:
```json

{
    "error": "Testset don't exist"
}

```
---

### PUT /api/v1/projects/{projectId}/testsets/{setId}/testruns/{runId}


#### Popis
Aktualizuje stav daneho testRun

#### Vstup
```json

{
	"Status" : "string"
}
```
Poznámka: pro status jsou povolené hodnoty: "Running", "Finished" a "Archived".

#### Odpovědi

1) **Úspěch**: návratový kód: 200

Obsah:

```json

{
    "Status": "string",
}

```



2) **Neúspěch**: návratový kód: 404

Popis: Projekt nenalezen

Obsah:
```json

{
    "error": "Project not found"
}

```

3) **Neúspěch**: návratový kód: 400

Popis: Uživatel nemá práva k projektu

Obsah:
```json

{
    "error": "Not rights to project"
}

```

4) **Neúspěch**: návratový kód: 404

Popis: Test set nenalezen

Obsah:
```json

{
    "error": "Testset don't exist"
}

```
---

### DELETE /api/v1/projects/{projectId}/testsets/{setId}/testruns/{runId}


#### Popis
Archivuje dany test run

#### Odpovědi

1) **Úspěch**: návratový kód: 200

Obsah:

```json

{
    "success": "Deleted"
}

```

2) **Neúspěch**: návratový kód: 404

Popis: Projekt nenalezen

Obsah:
```json

{
    "error": "Project not found"
}

```

3) **Neúspěch**: návratový kód: 400

Popis: Uživatel nemá práva k projektu

Obsah:
```json

{
    "error": "Not rights to project"
}

```

4) **Neúspěch**: návratový kód: 404

Popis: Test set nenalezen

Obsah:
```json

{
    "error": "Testset don't exist"
}

```

5) **Neúspěch**: návratový kód: 404

Popis: Test run nenalezen

Obsah:
```json

{
    "error": "Test run don't exist"
}

```
---

---

### PUT /api/v1/projects/{projectId}/testsets/{setId}/testruns/{runId}/testcase/{testCaseID}


#### Popis
Změní status testovaciho případu v rámci test run

#### Vstup
```json
{
	"Status" : "string"
}
```
Poznámka: pro status jsou povolené hodnoty: "Pass", "Fail" a "Blocked".

#### Odpovědi

1) **Úspěch**: návratový kód: 200

Obsah:

```json

{
    "TestRun_id": "number",
    "TestSet_id": "number",
    "Status": "string",
    "TestCase": [
        {
            "TestCase_id": "number",
            "Name": "string",
            "Status": "string"
        }
    ]
}
```

2) **Neúspěch**: návratový kód: 404

Popis: Projekt nenalezen

Obsah:
```json

{
    "error": "Project not found"
}

```

3) **Neúspěch**: návratový kód: 400

Popis: Uživatel nemá práva k projektu

Obsah:
```json

{
    "error": "Not rights to project"
}

```

4) **Neúspěch**: návratový kód: 404

Popis: Test set nenalezen

Obsah:
```json

{
    "error": "Testset don't exist"
}

```

5) **Neúspěch**: návratový kód: 404

Popis: Test run nenalezen

Obsah:
```json

{
    "error": "Test run don't exist"
}

```
---
