# Rynet Monitoring Website
By Ryan Rowe

### Credentials
The login system uses a PHP backend with time-based, unique hashed tokens stored as cookies. The default session time is 7 days and cookie expiration times are enforced.

Credetials are to be stored in `/utils/credentials.json`
They must be valid json in the following format:
```
[
    {
        "name":     "Name1",
        "username": "user1",
        "password": "password1"
    },
    {
        "name":     "Name2",
        "username": "user2",
        "password": "password2"
    },
    ...
]
```
### Servers
Servers are to be stored in `/utils/servers.json`
They must be valid json in the following format:
* The `icon` value will have `.svg` appended and must be located in `/images`
* The HTTP server running on `url` must have an appropriate value for `Access-control-allow-origin` to allow for Ajax requests.
```
[
    {
        "name":"Server1",
        "url":"https://api.server1.com",
        "icon":"icon1"
    },{
        "name":"Server2",
        "url":"https://api.server2.com",
        "icon":"icon2"
    },
    ...
]
```
### API
The API has two requirements.
1. It must return valid json.
2. It _must_ have an `id` value that matches a corresponding `name` in `servers.json`
`https://api.server1.com` might return
* Plain text pairs will be displayed.
* Truthy pairs will be shown as sections with their own status indicators.
* Pairs with `total` and `used` values will be shown as progress bars.
* `Uptime` will be converted to `w d hh:mm:ss` format.
```
{
    "id":"Server1",
    "Public IP":"xxx.xxx.xxx.xxx",
    "eth0":"yyy.yyy.yyy.yyy",
    "Service1":true,
    "Service2":true,
    "Service3":true,
    "Memory":{
        "total":1024,
        "used":40
    },
    "Swap":{
        "total":1024,
        "used":0 
    },
    "Users":0,
    "Uptime":498655.34,
    "Load":"0.00 0.00 0.00",
    "Date":"Thu Jun 23 12:44:05 EDT 2016"
}
```