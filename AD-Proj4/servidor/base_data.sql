
/*
Aplicações Distribuídas - Projeto 3 - Cliente.py
Grupo: 6
Números de aluno: Francisco Mendonça 46602, Miguel Marques 47139, Susana Vieira 46598
*/


PRAGMA foreign_keys = ON;

CREATE TABLE alunos (
id INTEGER PRIMARY KEY AUTOINCREMENT,
nome TEXT,
nacionalidade TEXT,
idade INTEGER
);

CREATE TABLE disciplina (
id INTEGER PRIMARY KEY AUTOINCREMENT,
designacao TEXT,
ano INTEGER,
semestre INTEGER
);

CREATE TABLE turma (
id INTEGER PRIMARY KEY AUTOINCREMENT,
id_disciplina INTEGER,
tipo TEXT,
designacao TEXT,
FOREIGN KEY(id_disciplina) REFERENCES disciplina(id) ON DELETE CASCADE
);

CREATE TABLE inscricoes (
id_aluno INTEGER,
id_turma INTEGER,
ano_letivo TEXT,
PRIMARY KEY (id_aluno, id_turma),
FOREIGN KEY(id_aluno) REFERENCES alunos(id) ON DELETE CASCADE,
FOREIGN KEY(id_turma) REFERENCES turma(id) ON DELETE CASCADE
);