package main
import(
	"os"
	"fmt"
	"regexp"
	"os/exec"
	"os/user"
	"strconv"
	"strings"
	"net/http"
	"io/ioutil"
	"encoding/json"
)

var COMMAND string
var USER string
var FILE string
var CMD string
var SERVER_NAME string
var SERVER_PASSWD string
var SERVER_RCON string
var SERVER_ADDONS string
var user_new string

const (
    PORT		= ":8081"
    SSL_CRT		= "/root/site.crt"
    SSL_KEY		= "/root/site.key"
	SECRET_KEY	= "IwEXsHv4FoQz5iwwPsOgw98jVYqbJHsq"
)

type Foo struct {
	Name	string	`json:"name"`
	Size	int64	`json:"size"`
	Time	string	`json:"time"`
}

func handler(w http.ResponseWriter, r *http.Request) {
	ip := strings.Split(r.RemoteAddr,":")[0]
	w.Header().Set("Content-Type", "text/plain")
	
	key := r.URL.Query().Get("key")
	if len(key) == 0 {
		fmt.Fprintf(w, "No key")
		return
	}
	
	if(SECRET_KEY != key){
		fmt.Fprintf(w, "no access: "+ip+" AND key: "+key)
		return
	}
	
	COMMAND = r.URL.Query().Get("command")
	if len(COMMAND) == 0 {
		fmt.Fprintf(w, "No command")
		return
	}
	
	switch COMMAND {
		default:
			fmt.Fprintf(w, "Nothing to do...")
			return
				
		case "csgo":
			USER = "csgoserver"+r.URL.Query().Get("user")
			if len(USER) == 0 {
				fmt.Fprintf(w, "No user")
				return
			}
			
			re := regexp.MustCompile("^[a-z0-9]*$")
			if(re.MatchString(USER) == false){
				fmt.Fprintf(w, "wrong user")
				return
			}
			
			if(USER == "root"){
				fmt.Fprintf(w, "not root")
				return
			}
			
			CMD = r.URL.Query().Get("cmd")
			if len(CMD) == 0 {
				fmt.Fprintf(w, "No cmd")
				return
			}
			
			is_stop_start_restart := map[string]bool { 
				"restart": true, "start": true, "stop": true, "log": true, "update-restart": true, "gotv": true, "delete": true, "cnf": true, "addons": true, "create": true,
			}
			if !is_stop_start_restart[CMD] {
				fmt.Fprintf(w, "Wrong cmd")
				return
			}
			
			if(CMD == "delete"){
			FILE = r.URL.Query().Get("file")
			if len(FILE) == 0 {
				fmt.Fprintf(w, "No file")
				return
			}
			
			re := regexp.MustCompile("^[a-zA-Z0-9_.-]*$")
			if(re.MatchString(FILE) == false){
				fmt.Fprintf(w, "wrong file")
				return
			}
			
			err := os.Remove("/home/"+USER+"/serverfiles/csgo/GOTV/"+FILE)
				if err != nil {
					fmt.Fprintf(w, "error no file")
					return
				}
			fmt.Fprintf(w, "OK")
			return
			}
			
			if(CMD == "log"){
				out, err := ioutil.ReadFile("/home/"+USER+"/log/console/csgo-server-console.log")
				if err != nil {
					fmt.Fprintf(w, "error")
					return
				}
				fmt.Fprintf(w, string(out))
				return
			}
			
			if (CMD == "cnf") {
				SERVER_NAME = r.URL.Query().Get("server_name")
				SERVER_PASSWD = r.URL.Query().Get("server_passwd")
				SERVER_RCON = r.URL.Query().Get("server_rcon")
				SERVER_ADDONS = r.URL.Query().Get("server_addons")
				re := regexp.MustCompile("^[a-zA-Z0-9_. -]*$")
				
				// Проверка на пустоту
				if len(SERVER_NAME) == 0 {
					fmt.Fprintf(w, "empty server name")
					return
				}
				if len(SERVER_PASSWD) == 0 {
					fmt.Fprintf(w, "empty server passwd")
					return
				}
				if len(SERVER_RCON) == 0 {
					fmt.Fprintf(w, "empty server rcon")
					return
				}
				if len(SERVER_ADDONS) == 0 {
					fmt.Fprintf(w, "empty server addons")
					return
				}
				
				// Проверка на допустимые символы
				if(re.MatchString(SERVER_NAME) == false){
					fmt.Fprintf(w, "wrong server name")
					return
				}
				if(re.MatchString(SERVER_PASSWD) == false){
					fmt.Fprintf(w, "wrong server name")
					return
				}
				if(re.MatchString(SERVER_RCON) == false){
					fmt.Fprintf(w, "wrong server name")
					return
				}
				if(re.MatchString(SERVER_ADDONS) == false){
					fmt.Fprintf(w, "wrong server addons")
					return
				}
				
				out, err := ioutil.ReadFile("/home/"+USER+"/serverfiles/csgo/cfg/csgo-server.cfg")
				if err != nil {
					fmt.Fprintf(w, "error")
					return
				}
				
				lines := strings.Split(string(out), "\n")
				for i, line := range lines {
					if strings.Contains(line, "hostname") {
						lines[i] = `hostname "`+SERVER_NAME+`"`
					}
					if strings.Contains(line, "sv_password") {
						lines[i] = `sv_password "`+SERVER_PASSWD+`"`
					}
					if strings.Contains(line, "rcon_password") {
						lines[i] = `rcon_password "`+SERVER_RCON+`"`
					}
				}
				output := strings.Join(lines, "\n")
				err = ioutil.WriteFile("/home/"+USER+"/serverfiles/csgo/cfg/csgo-server.cfg", []byte(output), 0644)
				if err != nil {
					fmt.Fprintf(w, "error")
				}
				
				if(SERVER_ADDONS == `1`){
					err := os.Chmod("/home/"+USER+"/serverfiles/csgo/addons/metamod.vdf", 0644)
					if err != nil {
						fmt.Fprintf(w, "error")
						return
					}
				}else{
					err := os.Chmod("/home/"+USER+"/serverfiles/csgo/addons/metamod.vdf", 0000)
					if err != nil {
						fmt.Fprintf(w, "error")
						return
					}
				}
				fmt.Fprintf(w, "OK")
				return
			}
			
			if(CMD == "gotv"){
				var i int
				datas := make(map[string]Foo)
				files, _ := ioutil.ReadDir("/home/"+USER+"/serverfiles/csgo/GOTV/")
					for _, f := range files {
					
						file, err := os.Stat("/home/"+USER+"/serverfiles/csgo/GOTV/"+f.Name())
						if err != nil {
							fmt.Fprintf(w, "error")
							return
						}
						
						datas[fmt.Sprint(i)] = Foo{Name: f.Name(), Size: file.Size(), Time: file.ModTime().Format("2006.01.02 15:04:05")}
						i++
					}
				j, _ := json.Marshal(datas)
				fmt.Fprintf(w, string(j))
				return
			}
			
			if(CMD == "create"){
				MAX := r.URL.Query().Get("MAX")
				GPORT := r.URL.Query().Get("GPORT")
				SPORT := r.URL.Query().Get("SPORT")
				CPORT := r.URL.Query().Get("CPORT")
				re := regexp.MustCompile("^[0-9]*$")
			
				// Проверка на пустоту
				if len(GPORT) == 0 {
					fmt.Fprintf(w, "empty game port")
					return
				}
				if len(SPORT) == 0 {
					fmt.Fprintf(w, "empty sourcetv port")
					return
				}
				if len(CPORT) == 0 {
					fmt.Fprintf(w, "empty client port")
					return
				}
				
				// Проверка на символы
				if(re.MatchString(GPORT) == false){
					fmt.Fprintf(w, "wrong game port name")
					return
				}
				if(re.MatchString(SPORT) == false){
					fmt.Fprintf(w, "wrong sourcetv port name")
					return
				}
				if(re.MatchString(CPORT) == false){
					fmt.Fprintf(w, "wrong client port name")
					return
				}
				
				out, err := exec.Command("adduser", "--disabled-login", USER).Output()
				if err != nil{ 
					fmt.Fprintf(w, "error")
					return
				}
		
				out, err = exec.Command("cp", "/root/install/csgoserver", "/home/"+USER+"/csgoserver").Output()
				if err != nil{ 
					fmt.Fprintf(w, "error")
					return
				}
		
				out, err = ioutil.ReadFile("/home/"+USER+"/csgoserver")
				if err != nil {
					fmt.Fprintf(w, "error")
					return
				}
					
				lines := strings.Split(string(out), "\n")
				for i, line := range lines {
					if strings.Contains(line, "maxplayers=") {
					lines[i] = `maxplayers="`+MAX+`"`
					}
					if strings.Contains(line, "gameport=") {
						lines[i] = `gameport="`+GPORT+`"`
					}
					if strings.Contains(line, "sourcetvport=") {
						lines[i] = `sourcetvport="`+SPORT+`"`
					}
					if strings.Contains(line, "clientport=") {
							lines[i] = `clientport="`+CPORT+`"`
					}
				}
				
				output := strings.Join(lines, "\n")
				err = ioutil.WriteFile("/home/"+USER+"/csgoserver", []byte(output), 0644)
				if err != nil {
					fmt.Fprintf(w, "error")
					return
				}
				
				out, err = exec.Command("cp", "/root/install/copy.sh", "/home/"+USER+"/copy.sh").Output()
				if err != nil{ 
					fmt.Fprintf(w, "error")
					return
				}
				
				out, err = ioutil.ReadFile("/home/"+USER+"/copy.sh")
				if err != nil {
					fmt.Fprintf(w, "error")
					return
				}
				
				lines = strings.Split(string(out), "\n")
				for i, line := range lines {
					if strings.Contains(line, "DIR=") {
					lines[i] = `DIR="/home/`+USER+`"`
					}
				}
				
				output = strings.Join(lines, "\n")
				err = ioutil.WriteFile("/home/"+USER+"/copy.sh", []byte(output), 0644)
				if err != nil {
					fmt.Fprintf(w, "error")
					return
				}
		
				usr, err := user.Lookup(USER)
				if err != nil {
					fmt.Fprintf(w, "error")
					return
				}
		
				UID, _ := strconv.Atoi(usr.Uid)
				GID, _ := strconv.Atoi(usr.Gid) 
		
				err = os.Chown("/home/"+USER+"/csgoserver", UID, GID)
				if err != nil{ 
					fmt.Fprintf(w, "error")
					return
				}
				
				err = os.Chown("/home/"+USER+"/copy.sh", UID, GID)
				if err != nil{ 
					fmt.Fprintf(w, "error")
					return
				}
				
				out, err = exec.Command("/home/"+USER+"/copy.sh").Output()				
				CMD := "/home/"+USER+"/csgoserver update-restart"
				out, err = exec.Command("su", "-", USER, "-c", CMD).Output()
				return
			}
		
			cmd := exec.Command("su", "-", USER, "-c", "/home/"+USER+"/csgoserver "+CMD)
			_, err := cmd.Output()
			if err != nil {
				fmt.Fprintf(w, "error")
				return
			}
			
			fmt.Fprintf(w, "OK")
			return
	}
}

func main() {
	http.HandleFunc("/", handler)
	http.ListenAndServeTLS(PORT, SSL_CRT, SSL_KEY, nil)
}
