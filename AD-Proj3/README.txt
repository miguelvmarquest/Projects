Aplicações Distribuidas
Projecto 3 - 2015/2016
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



