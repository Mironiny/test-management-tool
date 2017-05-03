# Basic script which send some request via rest api to the test-management-tool.
# Be sure you setup host and api_token variable

import http.client

host = "localhost:8000"
api_token = "gQyX1Bvz8qUjXOzAVNeH8T72gt7RN8TWaqLiI5TskzxlqpYfbGwYMfZ7j57c"

# Connection
conn = http.client.HTTPConnection(host)

# Create a header of http request
headers = {
    'authorization': "Bearer " + api_token,
    'content-type': "application/json",
    'cache-control': "no-cache",
    'postman-token': "44709a5c-ca4a-bbce-4b24-f0632a29bde4"
    }

################################################
payload = "{\n    \"Name\": \"Create and edit project\"\n}"
conn.request("POST", "/api/v1/testsuites", payload, headers)
###

res = conn.getresponse()
data = res.read()

payload = "{\n    \"Name\": \"Create and edit requirement\"\n}"
conn.request("POST", "/api/v1/testsuites", payload, headers)

res = conn.getresponse()
data = res.read()

payload = "{\n    \"TestSuite_id\": 1,\n    \"Name\": \"Not selected project\"\n}"
conn.request("POST", "/api/v1/testcases", payload, headers)

res = conn.getresponse()
data = res.read()

payload = "{\n    \"TestSuite_id\": 1,\n    \"Name\": \"Create project\"\n}"
conn.request("POST", "/api/v1/testcases", payload, headers)

res = conn.getresponse()
data = res.read()

payload = "{\n    \"TestSuite_id\": 1,\n    \"Name\": \"Create project without name\"\n}"
conn.request("POST", "/api/v1/testcases", payload, headers)

res = conn.getresponse()
data = res.read()

payload = "{\n    \"TestSuite_id\": 1,\n    \"Name\": \"Check if overview contains project\"\n}"
conn.request("POST", "/api/v1/testcases", payload, headers)

res = conn.getresponse()
data = res.read()

payload = "{\n    \"TestSuite_id\": 1,\n    \"Name\": \"Edit project\"\n}"
conn.request("POST", "/api/v1/testcases", payload, headers)

res = conn.getresponse()
data = res.read()

################################################

###

payload = "{\n    \"TestSuite_id\": 2,\n    \"Name\": \"Create project\"\n}"
conn.request("POST", "/api/v1/testcases", payload, headers)

res = conn.getresponse()
data = res.read()

payload = "{\n    \"TestSuite_id\": 2,\n    \"Name\": \"Create requirement\"\n}"
conn.request("POST", "/api/v1/testcases", payload, headers)

res = conn.getresponse()
data = res.read()

payload = "{\n    \"TestSuite_id\": 2,\n    \"Name\": \"Create requirement without name\"\n}"
conn.request("POST", "/api/v1/testcases", payload, headers)

res = conn.getresponse()
data = res.read()

payload = "{\n    \"TestSuite_id\": 2,\n    \"Name\": \"Overview contains requirement\"\n}"
conn.request("POST", "/api/v1/testcases", payload, headers)

res = conn.getresponse()
data = res.read()

payload = "{\n    \"TestSuite_id\": 2,\n    \"Name\": \"Edit requirement\"\n}"
conn.request("POST", "/api/v1/testcases", payload, headers)

res = conn.getresponse()
data = res.read()

payload = "{\n    \"TestSuite_id\": 2,\n    \"Name\": \"Cover requirement\"\n}"
conn.request("POST", "/api/v1/testcases", payload, headers)

res = conn.getresponse()
data = res.read()

################################################
payload = "{\n    \"Name\": \"Create and edit TestSuites and TestCase\"\n}"
conn.request("POST", "/api/v1/testsuites", payload, headers)
###

res = conn.getresponse()
data = res.read()

payload = "{\n    \"TestSuite_id\": 3,\n    \"Name\": \"Create test suite\"\n}"
conn.request("POST", "/api/v1/testcases", payload, headers)

res = conn.getresponse()
data = res.read()

payload = "{\n    \"TestSuite_id\": 3,\n    \"Name\": \"Create test suite without name\"\n}"
conn.request("POST", "/api/v1/testcases", payload, headers)

res = conn.getresponse()
data = res.read()

payload = "{\n    \"TestSuite_id\": 3,\n    \"Name\": \"Check if overview contains suite\"\n}"
conn.request("POST", "/api/v1/testcases", payload, headers)

res = conn.getresponse()
data = res.read()

payload = "{\n    \"TestSuite_id\": 3,\n    \"Name\": \"Edit test suite\"\n}"
conn.request("POST", "/api/v1/testcases", payload, headers)

res = conn.getresponse()
data = res.read()

payload = "{\n    \"TestSuite_id\": 3,\n    \"Name\": \"Create test case without details\"\n}"
conn.request("POST", "/api/v1/testcases", payload, headers)

res = conn.getresponse()
data = res.read()

payload = "{\n    \"TestSuite_id\": 3,\n    \"Name\": \"Create test case with details\"\n}"
conn.request("POST", "/api/v1/testcases", payload, headers)

res = conn.getresponse()
data = res.read()

payload = "{\n    \"TestSuite_id\": 3,\n    \"Name\": \"Create test case without name\"\n}"
conn.request("POST", "/api/v1/testcases", payload, headers)

res = conn.getresponse()
data = res.read()

payload = "{\n    \"TestSuite_id\": 3,\n    \"Name\": \"Check if overview contains case\"\n}"
conn.request("POST", "/api/v1/testcases", payload, headers)

res = conn.getresponse()
data = res.read()

payload = "{\n    \"TestSuite_id\": 3,\n    \"Name\": \"Edit test case\"\n}"
conn.request("POST", "/api/v1/testcases", payload, headers)

res = conn.getresponse()
data = res.read()

################################################
payload = "{\n    \"Name\": \"Create test set and run\"\n}"
conn.request("POST", "/api/v1/testsuites", payload, headers)
###

res = conn.getresponse()
data = res.read()

payload = "{\n    \"TestSuite_id\": 4,\n    \"Name\": \"Create project\"\n}"
conn.request("POST", "/api/v1/testcases", payload, headers)

res = conn.getresponse()
data = res.read()

payload = "{\n    \"TestSuite_id\": 4,\n    \"Name\": \"Create set\"\n}"
conn.request("POST", "/api/v1/testcases", payload, headers)

res = conn.getresponse()
data = res.read()

payload = "{\n    \"TestSuite_id\": 4,\n    \"Name\": \"Overview contains set\"\n}"
conn.request("POST", "/api/v1/testcases", payload, headers)

res = conn.getresponse()
data = res.read()

payload = "{\n    \"TestSuite_id\": 4,\n    \"Name\": \"Create set without name\"\n}"
conn.request("POST", "/api/v1/testcases", payload, headers)

res = conn.getresponse()
data = res.read()

payload = "{\n    \"TestSuite_id\": 4,\n    \"Name\": \"Create set without tests\"\n}"
conn.request("POST", "/api/v1/testcases", payload, headers)

res = conn.getresponse()
data = res.read()

payload = "{\n    \"TestSuite_id\": 4,\n    \"Name\": \"Edit test set\"\n}"
conn.request("POST", "/api/v1/testcases", payload, headers)

res = conn.getresponse()
data = res.read()

payload = "{\n    \"TestSuite_id\": 4,\n    \"Name\": \"Create test run\"\n}"
conn.request("POST", "/api/v1/testcases", payload, headers)

res = conn.getresponse()
data = res.read()

payload = "{\n    \"TestSuite_id\": 4,\n    \"Name\": \"Overview contains run\"\n}"
conn.request("POST", "/api/v1/testcases", payload, headers)

res = conn.getresponse()
data = res.read()

payload = "{\n    \"TestSuite_id\": 4,\n    \"Name\": \"Execute contains tests\"\n}"
conn.request("POST", "/api/v1/testcases", payload, headers)

res = conn.getresponse()
data = res.read()


################################################
payload = "{\n    \"Name\": \"Registration and log test\"\n}"
conn.request("POST", "/api/v1/testsuites", payload, headers)
###

res = conn.getresponse()
data = res.read()

payload = "{\n    \"TestSuite_id\": 5,\n    \"Name\": \"Redirect to login page\"\n}"
conn.request("POST", "/api/v1/testcases", payload, headers)

res = conn.getresponse()
data = res.read()

payload = "{\n    \"TestSuite_id\": 5,\n    \"Name\": \"Registration\"\n}"
conn.request("POST", "/api/v1/testcases", payload, headers)

res = conn.getresponse()
data = res.read()

payload = "{\n    \"TestSuite_id\": 5,\n    \"Name\": \"Registrate same user\"\n}"
conn.request("POST", "/api/v1/testcases", payload, headers)

res = conn.getresponse()
data = res.read()

payload = "{\n    \"TestSuite_id\": 5,\n    \"Name\": \"Log and logout\"\n}"
conn.request("POST", "/api/v1/testcases", payload, headers)
