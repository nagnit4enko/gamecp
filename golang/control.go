package main
import(
	"os"
	"fmt"
	"regexp"
	"os/exec"
	"strings"
	"net/http"
	"io/ioutil"
	"encoding/json"
)

var COMMAND string
var USER string
var FILE string
var CMD string

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
			USER = r.URL.Query().Get("user")
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
			
			//if!(CMD == "restart" || CMD == "start" || CMD == "stop" || CMD == "log" || CMD == "update" || CMD == "gotv" || CMD == "delete" || CMD == "cnf"){
			//	fmt.Fprintf(w, "Wrong cmd")
			//	return
			//}
			
			is_stop_start_restart := map[string]bool { 
				"restart": true, "start": true, "stop": true, "log": true, "update": true, "gotv": true, "delete": true, "cnf": true,
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
			}
			
			if(CMD == "cnf"){
				out, err := ioutil.ReadFile("/home/"+USER+"/serverfiles/csgo/cfg/csgo-server.cfg")
				if err != nil {
					fmt.Fprintf(w, "error")
					return
				}
				fmt.Fprintf(w, string(out))
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
					
					datas[fmt.Sprint(i)] = Foo{Name: f.Name(), Size: file.Size(), Time: file.ModTime().Format("2006-01-02 15:04:05")}
					i++
				}
			j, _ := json.Marshal(datas)
			fmt.Fprintf(w, string(j))
			return
			}
			
			cmd := exec.Command("su", "-", USER, "-c", "/home/"+USER+"/csgoserver "+CMD)
			_, err := cmd.Output()
			//out, err := cmd.Output()
			if err != nil {
				//fmt.Fprintf(w, err.Error()) => это запишем в errors.log
				fmt.Fprintf(w, "error")
				return
			}
			
			//fmt.Fprintf(w, string(out)) => это тоже можно записывать, например в success.log
			fmt.Fprintf(w, "OK")
			return
	}
}

func main() {
	http.HandleFunc("/", handler)
	http.ListenAndServeTLS(PORT, SSL_CRT, SSL_KEY, nil)
}
