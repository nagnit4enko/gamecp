package main
import(
	"fmt"
	"regexp"
	"os/exec"
	"strings"
	"net/http"
	"io/ioutil"
)

var COMMAND string
var USER string
var CMD string

const (
    PORT		= ":8081"
    SSL_CRT		= "./site.crt"
    SSL_KEY		= "./site.key"
	SECRET_KEY	= "IwEXsHv4FoQz5iwwPsOgw98jVYqbJHsq"
)


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
			
			if!(CMD == "restart" || CMD == "start" || CMD == "stop" || CMD == "log" || CMD == "update"){
				fmt.Fprintf(w, "Wrong cmd")
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
