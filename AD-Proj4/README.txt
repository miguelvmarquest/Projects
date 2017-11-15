Aplicações Distribuidas
Projecto 4 - 2015/2016
Alunos: Francisco Mendonça 46602, Miguel Marques 47139, Susana Vieira 46598
Grupo: 06


Está tudo como pedido no enunciado do projecto

Para criar a base de dados o servidor utiliza o ficheiro base_data.sql

Os parâmetros para os pedidos ao servidor estão iguais, excepto nos seguintes:

	SHOW ALL ALUNOS <id_turma> -> SHOW ALL ALUNOS DISCIPLINA <id_turma>

	SHOW ALL ALUNOS <id_disciplina> -> SHOW ALL ALUNOS DISCIPLINA <id_disciplina>

	SHOW ALL TURMAS <id_disciplina> -> SHOW ALL TURMAS DISCIPLINA <id_disciplina>



EXEMPLOS:


Ao inserir um aluno (ou qualquer outra inserção) acontece o seguinte:

ADD | REMOVE | SHOW
comando > add aluno Portugal 143 Quimzé               

Status Code: 201 (Created)
Date: Sat, 30 Apr 2016 15:35:52 GMT
Content-Length: 0
Content-Type: text/html; charset=utf-8
Location: http://localhost:5000/alunos/17
Server: Werkzeug/0.10.1 Python/2.7.10



Ao fazer uma pesquisa na base de dados:

ADD | REMOVE | SHOW
comando > show all alunos turma 8

Status Code: 200 (Ok)
Date: Sat, 30 Apr 2016 15:31:33 GMT
Content-Length: 64
Content-Type: text/html; charset=utf-8
Server: Werkzeug/0.10.1 Python/2.7.10

Id: 8 | Nome: Andre | Nacionalidade: PT | Idade: 54




Regras do comando iptables:


	ping: sudo iptables -A INPUT -s nemo.alunos.di.fc.ul.pt

Configuração iptables

Metodo de teste:
O teste para estas regras foi verificar se a maquina continuava com acesso a internet,,
excepto para o ping por parte do servidor nemo.alunos.di.fc.ul.pt, e a ssh.
Para o primeiro foi feito um ping ao servidor e verificou-se se deu resposta
No segundo, numa outra maquina no mesmo local foi feito o acesso por ssh.

Máquinas que são aceites

sudo iptables -A OUTPUT -s 10.101.253.11 -j ACCEPT
sudo iptables -A OUTPUT -s 10.101.253.12 -j ACCEPT
sudo iptables -A OUTPUT -s 10.101.253.13 -j ACCEPT
sudo iptables -A OUTPUT -s 10.101.249.63 -j ACCEPT
sudo iptables -A OUTPUT -s 10.101.85.6 -j ACCEPT
sudo iptables -A OUTPUT -s 10.101.85.138 -j ACCEPT
sudo iptables -A OUTPUT -s 10.101.85.18 -j ACCEPT
sudo iptables -A OUTPUT -s 10.101.148.1 -j ACCEPT
sudo iptables -A OUTPUT -s 10.101.85.134 -j ACCEPT

ligação ao porto:

sudo iptables -A INPUT -p tcp --dport 5000 -j ACCEPT

Resultado das maquinas necessarias para o funcionamento:
Chain OUTPUT (policy ACCEPT)
target     prot opt source               destination         
ACCEPT     all  --  10.101.253.11        anywhere            
ACCEPT     all  --  10.101.253.12        anywhere            
ACCEPT     all  --  10.101.253.13        anywhere            
ACCEPT     all  --  10.101.249.63        anywhere            
ACCEPT     all  --  iate.alunos.di.fc.ul.pt  anywhere            
ACCEPT     all  --  falua.di.fc.ul.pt    anywhere            
ACCEPT     all  --  nemo.alunos.di.fc.ul.pt  anywhere            
ACCEPT     all  --  submarino.alunos.di.fc.ul.pt  anywhere            
ACCEPT     all  --  farol.alunos.di.fc.ul.pt  anywhere

Isto significa que a firewall permite que a maquina fale com qualquer destes servidores



Resposta a pings da máquina nemo.alunos.di.fc.ul.pt

sudo iptables -A OUTPUT -s 10.101.85.18 -p icmp -j ACCEPT
sudo iptables -A INPUT -s 10.101.85.18 -p icmp -j ACCEPT

Resultado da inserçao dos comandos
OUTPUT:
Chain OUTPUT (policy ACCEPT)
target     prot opt source               destination         
ACCEPT     all  --  10.101.253.11        anywhere            
ACCEPT     all  --  10.101.253.12        anywhere            
ACCEPT     all  --  10.101.253.13        anywhere            
ACCEPT     all  --  10.101.249.63        anywhere            
ACCEPT     all  --  iate.alunos.di.fc.ul.pt  anywhere            
ACCEPT     all  --  falua.di.fc.ul.pt    anywhere            
ACCEPT     all  --  nemo.alunos.di.fc.ul.pt  anywhere            
ACCEPT     all  --  submarino.alunos.di.fc.ul.pt  anywhere            
ACCEPT     all  --  farol.alunos.di.fc.ul.pt  anywhere            
ACCEPT     icmp --  nemo.alunos.di.fc.ul.pt  anywhere

INPUT:
Chain INPUT (policy ACCEPT)
target     prot opt source               destination         
ACCEPT     icmp --  nemo.alunos.di.fc.ul.pt  anywhere

Para verificar se o ping era permitido ao servidor nemo.alunos.di.fc.ul.pt
foi inserido o seguinte comando:

ping -c 3 10.101.85.18

E a resposta foi a seguinte:

PING 10.101.85.18 (10.101.85.18) 56(84) bytes of data.
64 bytes from 10.101.85.18: icmp_seq=1 ttl=63 time=0.258 ms
64 bytes from 10.101.85.18: icmp_seq=2 ttl=63 time=0.276 ms
64 bytes from 10.101.85.18: icmp_seq=3 ttl=63 time=0.269 ms

--- 10.101.85.18 ping statistics ---
3 packets transmitted, 3 received, 0% packet loss, time 1999ms
rtt min/avg/max/mdev = 0.258/0.267/0.276/0.020 ms


Permite a rececao e a resposta ao ping feito pelo servidor nemo.alunos.di.fc.ul.pt

Tráfego de Loopback

sudo iptables –A INPUT –i lo –j ACCEPT
sudo iptables –A OUTPUT –o lo –j ACCEPT

Resultado:

OUTPUT:
Chain OUTPUT (policy ACCEPT)
target     prot opt source               destination         
ACCEPT     all  --  10.101.253.11        anywhere            
ACCEPT     all  --  10.101.253.12        anywhere            
ACCEPT     all  --  10.101.253.13        anywhere            
ACCEPT     all  --  10.101.249.63        anywhere            
ACCEPT     all  --  iate.di.fc.ul.pt     anywhere            
ACCEPT     all  --  falua.di.fc.ul.pt    anywhere            
ACCEPT     all  --  nemo.alunos.di.fc.ul.pt  anywhere            
ACCEPT     all  --  submarino.alunos.di.fc.ul.pt  anywhere            
ACCEPT     all  --  farol.alunos.di.fc.ul.pt  anywhere            
ACCEPT     icmp --  nemo.alunos.di.fc.ul.pt  anywhere            
ACCEPT     all  --  anywhere             anywhere

INPUT:
Chain INPUT (policy ACCEPT)
target     prot opt source               destination         
ACCEPT     icmp --  nemo.alunos.di.fc.ul.pt  anywhere            
ACCEPT     all  --  anywhere             anywhere

Tráfego relacionado com uma ligação já establecida

sudo iptables –A INPUT –m state –-state ESTABLISHED,RELATED –j ACCEPT
sudo iptables –A OUTPUT –m state –-state ESTABLISHED,RELATED –j ACCEPT

Resultado:

OUTPUT:
Chain OUTPUT (policy ACCEPT)
target     prot opt source               destination         
ACCEPT     all  --  10.101.253.11        anywhere            
ACCEPT     all  --  10.101.253.12        anywhere            
ACCEPT     all  --  10.101.253.13        anywhere            
ACCEPT     all  --  10.101.249.63        anywhere            
ACCEPT     all  --  iate.di.fc.ul.pt     anywhere            
ACCEPT     all  --  falua.di.fc.ul.pt    anywhere            
ACCEPT     all  --  nemo.alunos.di.fc.ul.pt  anywhere            
ACCEPT     all  --  submarino.alunos.di.fc.ul.pt  anywhere            
ACCEPT     all  --  farol.alunos.di.fc.ul.pt  anywhere            
ACCEPT     icmp --  nemo.alunos.di.fc.ul.pt  anywhere            
ACCEPT     all  --  anywhere             anywhere            
ACCEPT     all  --  anywhere             anywhere             state RELATED,ESTABLISHED


INPUT:
Chain INPUT (policy ACCEPT)
target     prot opt source               destination         
ACCEPT     icmp --  nemo.alunos.di.fc.ul.pt  anywhere            
ACCEPT     all  --  anywhere             anywhere            
ACCEPT     all  --  anywhere             anywhere             state RELATED,ESTABLISHED

Ligações SSH

sudo iptables -A INPUT -p tcp --dport 22 -s 10.101.85.0/24 -j ACCEPT
sudo iptables -A INPUT -p tcp --dport 22 -s 127.0.0.0/8 -j ACCEPT
sudo iptables -A INPUT -p tcp --dport 22 -j DROP



Resultados:
OUTPUT:
Chain OUTPUT (policy ACCEPT)
target     prot opt source               destination         
ACCEPT     all  --  10.101.253.11        anywhere            
ACCEPT     all  --  10.101.253.12        anywhere            
ACCEPT     all  --  10.101.253.13        anywhere            
ACCEPT     all  --  10.101.249.63        anywhere            
ACCEPT     all  --  iate.alunos.di.fc.ul.pt  anywhere            
ACCEPT     all  --  falua.di.fc.ul.pt    anywhere            
ACCEPT     all  --  nemo.alunos.di.fc.ul.pt  anywhere            
ACCEPT     all  --  submarino.alunos.di.fc.ul.pt  anywhere            
ACCEPT     all  --  farol.alunos.di.fc.ul.pt  anywhere            
ACCEPT     icmp --  nemo.alunos.di.fc.ul.pt  anywhere            
ACCEPT     all  --  anywhere             anywhere            
ACCEPT     all  --  anywhere             anywhere             state RELATED,ESTABLISHED


INPUT:
Chain INPUT (policy ACCEPT)
target     prot opt source               destination         
ACCEPT     icmp --  nemo.alunos.di.fc.ul.pt  anywhere            
ACCEPT     all  --  anywhere             anywhere            
ACCEPT     all  --  anywhere             anywhere             state RELATED,ESTABLISHED
ACCEPT     tcp  --  10.101.85.0/24       anywhere             tcp dpt:ssh
ACCEPT     tcp  --  127.0.0.0/8          anywhere             tcp dpt:ssh
DROP       tcp  --  anywhere             anywhere             tcp dpt:ssh

Permitir DNS - O DNS tem a porta 53 como porta oficial

sudo iptables -A OUTPUT -p tcp --dport 53 -j ACCEPT
sudo iptables -A INPUT -p tcp --dport 53 -j ACCEPT

Resultados:

OUTPUT:
Chain OUTPUT (policy ACCEPT)
target     prot opt source               destination         
ACCEPT     all  --  10.101.253.11        anywhere            
ACCEPT     all  --  10.101.253.12        anywhere            
ACCEPT     all  --  10.101.253.13        anywhere            
ACCEPT     all  --  10.101.249.63        anywhere            
ACCEPT     all  --  iate.di.fc.ul.pt     anywhere            
ACCEPT     all  --  falua.di.fc.ul.pt    anywhere            
ACCEPT     all  --  nemo.alunos.di.fc.ul.pt  anywhere            
ACCEPT     all  --  submarino.alunos.di.fc.ul.pt  anywhere            
ACCEPT     all  --  farol.alunos.di.fc.ul.pt  anywhere            
ACCEPT     icmp --  nemo.alunos.di.fc.ul.pt  anywhere            
ACCEPT     all  --  anywhere             anywhere            
ACCEPT     all  --  anywhere             anywhere             state RELATED,ESTABLISHED
ACCEPT     tcp  --  anywhere             anywhere             tcp dpt:domain

INPUT:
Chain INPUT (policy ACCEPT)
target     prot opt source               destination         
ACCEPT     icmp --  nemo.alunos.di.fc.ul.pt  anywhere            
ACCEPT     all  --  anywhere             anywhere            
ACCEPT     all  --  anywhere             anywhere             state RELATED,ESTABLISHED
ACCEPT     tcp  --  10.101.85.0/24       anywhere             tcp dpt:ssh
ACCEPT     tcp  --  127.0.0.0/8          anywhere             tcp dpt:ssh
DROP       tcp  --  anywhere             anywhere             tcp dpt:ssh
ACCEPT     tcp  --  anywhere             anywhere             tcp dpt:domain

UDP:

sudo iptables -A OUTPUT -p udp --dport 53 -j ACCEPT
sudo iptables -A INPUT -p udp --dport 53 -j ACCEPT

Resultados:

Chain OUTPUT (policy ACCEPT)
target     prot opt source               destination         
ACCEPT     all  --  10.101.253.11        anywhere            
ACCEPT     all  --  10.101.253.12        anywhere            
ACCEPT     all  --  10.101.253.13        anywhere            
ACCEPT     all  --  10.101.249.63        anywhere            
ACCEPT     all  --  iate.alunos.di.fc.ul.pt  anywhere            
ACCEPT     all  --  falua.di.fc.ul.pt    anywhere            
ACCEPT     all  --  nemo.alunos.di.fc.ul.pt  anywhere            
ACCEPT     all  --  submarino.alunos.di.fc.ul.pt  anywhere            
ACCEPT     all  --  farol.alunos.di.fc.ul.pt  anywhere            
ACCEPT     icmp --  nemo.alunos.di.fc.ul.pt  anywhere            
ACCEPT     all  --  anywhere             anywhere            
ACCEPT     all  --  anywhere             anywhere             state RELATED,ESTABLISHED
ACCEPT     tcp  --  anywhere             anywhere             tcp dpt:domain
ACCEPT     udp  --  anywhere             anywhere             udp dpt:domain

INPUT:
Chain INPUT (policy ACCEPT)
target     prot opt source               destination         
ACCEPT     icmp --  nemo.alunos.di.fc.ul.pt  anywhere            
ACCEPT     all  --  anywhere             anywhere            
ACCEPT     all  --  anywhere             anywhere             state RELATED,ESTABLISHED
ACCEPT     tcp  --  10.101.85.0/24       anywhere             tcp dpt:ssh
ACCEPT     tcp  --  127.0.0.0/8          anywhere             tcp dpt:ssh
DROP       tcp  --  anywhere             anywhere             tcp dpt:ssh
ACCEPT     tcp  --  anywhere             anywhere             tcp dpt:domain
ACCEPT     udp  --  anywhere             anywhere             udp dpt:domain


snort

alert tcp any any ->  any 1:1024 (msg:”Varrimento de portos";threshold:type both, count 10 , seconds 120;
sid:20160521; rev:0; flags:S,CE;)

alert tcp any any -> any any (msg:”Estão a tentar descobrir uma password de acesso ao servico"; threshold:type limit,track by_src, count 4 , seconds 30;
sid:20160521; rev:0; flags:S,CE;)
