#!/usr/bin/env python
# -*- coding: utf-8 -*-
"""
Aplicações Distribuídas - Projeto 3 - servidor.py
Grupo: 6
Números de aluno: Francisco Mendonça 46602, Miguel Marques 47139, Susana Vieira 46598
"""

from contextlib import closing
import sqlite3
from os.path import isfile
from flask import Flask, request, session, g, redirect, make_response, url_for, \
     abort, render_template, flash
import sqlite3, json

DATABASE = 'Novo.db'
DEBUG = True
SECRET_KEY = 'development key'
USERNAME = 'admin'
PASSWORD = 'default'

app = Flask(__name__)
app.config.from_object(__name__)


def connect_db():
    return sqlite3.connect(app.config['DATABASE'])



def init_db():
    with closing(connect_db()) as db:
        with app.open_resource('base_data.sql', mode='r') as f:
            db.cursor().executescript(f.read())
        db.commit()

db_is_created = isfile(app.config['DATABASE'])
if not db_is_created:
	init_db()

@app.before_request
def before_request():
    g.db = connect_db()

@app.teardown_request
def teardown_request(exception):
    db = getattr(g, 'db', None)
    if db is not None:
        db.close()




########-----ALUNOS----##############



@app.route('/alunos', methods = ["PUT"])
@app.route('/alunos/<int:id>', methods = ["GET", "DELETE"])
@app.route('/alunos/inscricao/<int:id>/<int:id_turma>', methods = ["PUT","GET","DELETE"])
@app.route('/alunos/<all>', methods = ["GET","DELETE"])
@app.route('/alunos/<all>/<tipo>/<int:id>', methods = ["GET","DELETE"])

def aluno(id = None,all=None, id_turma = None, tipo=None):
	g.db.execute('PRAGMA foreign_keys = ON;')
	g.db.commit()

	if request.method == "GET":
		# Ler dados do aluno com id na base de dados
		if id_turma!=None:
			try:
				cursor = g.db.execute('SELECT * FROM inscricoes WHERE id_aluno='+str(id)+' AND id_turma='+str(id_turma))
				todos=map(lambda x: {'id_aluno': x[0], 'id_turma': x[1], 'ano_letivo': x[2]},cursor.fetchall())
				r = make_response(json.dumps(todos))
				r.status_code = 200
			except:
				r = make_response()
				r.status_code = 500 
		else:
			if all=='all':
				if tipo == None:
					try:
						cursor = g.db.execute('SELECT * FROM alunos')
						todos=map(lambda x: {'id': x[0], 'nome': x[1], 'nacionalidade': x[2], 'idade': x[3]},cursor.fetchall())
						r = make_response(json.dumps(todos))
						r.status_code = 200
					except:
						r = make_response()
						r.status_code = 500 
				elif tipo == 'disciplina':
					try:
						cursor = g.db.execute('SELECT id From disciplina WHERE id=%d' % id)
						registos = cursor.fetchone()
						if registos!=None:
							cursor = g.db.execute('SELECT A.id,A.nome,A.nacionalidade,A.idade FROM alunos A, inscricoes I WHERE I.id_aluno = A.id and I.id_turma in (SELECT T.id FROM turma T WHERE T.id_disciplina=%s)' %id)
							todos=map(lambda x: {'id': x[0], 'nome': x[1], 'nacionalidade': x[2], 'idade': x[3]},cursor.fetchall())
							r = make_response(json.dumps(todos))
							r.status_code = 200
						else:
							r = make_response()
							r.status_code = 204
					except:
						r = make_response()
						r.status_code = 500 
				elif tipo == 'turma':
					try:
						cursor = g.db.execute('SELECT id From turma WHERE id=%d' % id)
						registos = cursor.fetchone()
						if registos!=None:
							cursor = g.db.execute('SELECT * FROM alunos A, inscricoes I WHERE A.id=I.id_aluno AND I.id_turma=%s' %id)
							todos=map(lambda x: {'id': x[0], 'nome': x[1], 'nacionalidade': x[2], 'idade': x[3]},cursor.fetchall())
							r = make_response(json.dumps(todos))
							r.status_code = 200
						else:
							r = make_response()
							r.status_code = 204
					except:
						r = make_response()
						r.status_code = 500 
			else:
				try:
					cursor = g.db.execute('SELECT * FROM alunos WHERE id=%d' % id) # Fazer query e obter todos	
					todos=map(lambda x: {'id': x[0], 'nome': x[1], 'nacionalidade': x[2], 'idade': x[3]},cursor.fetchall())
					r = make_response(json.dumps(todos))
					r.status_code = 200
				except:
					r = make_response()
					r.status_code = 500 
		

	if request.method == "PUT":
		if id_turma!= None:
			try:
				g.db.execute('INSERT INTO inscricoes VALUES (?, ?, ?)', (id,id_turma,'2015/2016'))
				g.db.commit()
				r = make_response()
				r.status_code = 201 
				r.headers['location'] = '/alunos/inscricao/'+str(id)+'/'+str(id_turma)	
			except:
				r = make_response()
				r.status_code = 500 
		else:
			try:
				data = json.loads(request.data)
				g.db.execute('INSERT INTO alunos VALUES (NULL, ?, ?, ?)', (data['nome'],data['nacionalidade'], data['idade']))
				g.db.commit()
				cursor = g.db.execute('SELECT id FROM alunos WHERE nome=? AND nacionalidade=? AND idade=?', (data['nome'],data['nacionalidade'], data['idade']))
				id = cursor.fetchone()
				r = make_response()
				r.status_code = 201 
				r.headers['location'] = '/alunos/'+str(id[0])
			except:
				r = make_response()
				r.status_code = 500 
		# Ler dados do aluno no pedido e inserir na base de dados
		# Em caso de sucesso responder com a localização do novo recurso

	
	if request.method == "DELETE":
		if id_turma!=None:
			try:
				cursor = g.db.execute('SELECT id FROM inscricoes WHERE id_aluno='+str(id)+' AND id_turma='+str(id_turma))
				registos = cursor.fetchone()
				if registos!=None:
					g.db.execute('DELETE FROM inscricoes WHERE id_aluno='+str(id)+' AND id_turma='+str(id_turma))
					g.db.commit()
					r = make_response()
					r.status_code = 200
				else:
					r = make_response()
					r.status_code = 204
			except:
				r = make_response()
				r.status_code = 500 

		elif all=='all':
			if tipo == None:
				try:
					cursor = g.db.execute('SELECT id From alunos')
					registos = cursor.fetchone()
					if registos!=None:
						g.db.execute('DELETE FROM alunos')
						g.db.commit()
						r = make_response()
						r.status_code = 200
					else:
						r = make_response()
						r.status_code = 204
				except:
					r = make_response()
					r.status_code = 500 

			elif tipo == 'disciplina':
				try:
					cursor = g.db.execute('SELECT id FROM alunos WHERE id in (SELECT id_aluno FROM inscricoes WHERE id_turma in (SELECT id From turma WHERE id_disciplina=%s))' %id)
					registos = cursor.fetchone()
					if registos!=None:
						g.db.execute('DELETE FROM alunos WHERE id in (SELECT id_aluno FROM inscricoes WHERE id_turma in (SELECT id From turma WHERE id_disciplina=%s))' %id)
						g.db.commit()
						r = make_response()
						r.status_code = 200
					else:
						r = make_response()
						r.status_code = 204
				except:
					r = make_response()
					r.status_code = 500 
			elif tipo == 'turma':
				try:
					cursor = g.db.execute('SELECT id From FROM alunos WHERE id in (SELECT id_aluno FROM inscricoes WHERE id_turma=%s'% id)
					registos = cursor.fetchone()
					if registos!=None:
						g.db.execute('DELETE FROM alunos WHERE id in (SELECT id_aluno FROM inscricoes WHERE id_turma=%s'% id)
						g.db.commit()
						r = make_response()
						r.status_code = 200
					else:
						r = make_response()
						r.status_code = 204
				except:
					r = make_response()
					r.status_code = 500 
		else:
			# g.db.execute('DELETE FROM inscricoes WHERE id_aluno=%s' % id)
			# g.db.commit()
			try:
				cursor = g.db.execute('SELECT id From alunos WHERE id=%d' % id)
				registos = cursor.fetchone()
				if registos!=None:
					g.db.execute('DELETE FROM alunos WHERE id=%s' % id)
					g.db.commit()
					r = make_response()
					r.status_code = 200
				else:
					r = make_response()
					r.status_code = 204
			except:
				r = make_response()
				r.status_code = 500 
	return r







########-----DISCIPLINAS----##############


@app.route('/disciplinas', methods = ["PUT"])
@app.route('/disciplinas/<int:id>', methods = ["GET", "DELETE"])
@app.route('/disciplinas/<all>', methods = ["GET","DELETE"])

def disciplinas(id = None, all=None):
	g.db.execute('PRAGMA foreign_keys = ON;')
	g.db.commit()

	if request.method == "GET":
		# Ler dados do aluno com id na base de dados
		if all=='all':
			try:
				cursor = g.db.execute('SELECT * FROM disciplina')
				todos=map(lambda x: {'id': x[0], 'designacao': x[1], 'ano': x[2], 'semestre': x[3]},cursor.fetchall())
				r = make_response(json.dumps(todos))
				r.status_code = 200
			except:
				r = make_response()
				r.status_code = 500

		else:
			try:
				cursor = g.db.execute('SELECT * FROM disciplina WHERE id=%d' % id) # Fazer query e obter todos
				todos=map(lambda x: {'id': x[0], 'designacao': x[1], 'ano': x[2], 'semestre': x[3]},cursor.fetchall())
				r = make_response(json.dumps(todos))
				r.status_code = 200
			except:
				r = make_response()
				r.status_code = 500


	if request.method == "PUT":
		try:
			data = json.loads(request.data)
			g.db.execute('INSERT INTO disciplina VALUES (NULL, ?, ?,?)', (data['designacao'],data['ano'],data['semestre']))
			g.db.commit()
			cursor = g.db.execute('SELECT id FROM disciplina WHERE designacao=? AND ano=? AND semestre=?', (data['designacao'],data['ano'],data['semestre']))
			id = cursor.fetchone()
			# Ler dados do aluno no pedido e inserir na base de dados
			# Em caso de sucesso responder com a localização do novo recurso
			r = make_response()
			r.status_code = 201
			r.headers['location'] = '/disciplinas/'+str(id[0])
		except:
			r = make_response()
			r.status_code = 500

	if request.method == "DELETE":
		if all=='all':
			# g.db.execute('DELETE FROM inscricoes')
			# g.db.commit()
			# g.db.execute('DELETE FROM turma')
			# g.db.commit()
			try:
				cursor = g.db.execute('SELECT id From disciplina')
				registos = cursor.fetchone()
				if registos!=None:
					g.db.execute('DELETE FROM disciplina')
					g.db.commit()
					r = make_response()
					r.status_code = 200
				else:
					r = make_response()
					r.status_code = 204
			except:
				r = make_response()
				r.status_code = 500 
		else:
			# g.db.execute('DELETE FROM inscricoes WHERE inscricoes.id_turma in (Select turma.id FROM turma WHERE turma.id_disciplina=%d)' % id)
			# g.db.commit()
			# g.db.execute('DELETE FROM turma WHERE id_disciplina=%d' % id)
			# g.db.commit()
			try:
				cursor = g.db.execute('SELECT id From disciplina WHERE id=%d' % id)
				registos = cursor.fetchone()
				if registos!=None:
					g.db.execute('DELETE FROM disciplina WHERE id=%d' % id)
					g.db.commit()
					r = make_response()
					r.status_code = 200
				else:
					r = make_response()
					r.status_code = 204
			except:
				r = make_response()
				r.status_code = 500 
	return r






########-----TURMAS----##############



@app.route('/turmas', methods = ["PUT"])
@app.route('/turmas/<int:id>', methods = ["GET", "DELETE"])
@app.route('/turmas/<all>', methods = ["GET","DELETE"])
@app.route('/turmas/<all>/<tipo>/<int:id>', methods = ["GET"])


def turmas(id = None, all=None, tipo=None):
	g.db.execute('PRAGMA foreign_keys = ON;')
	g.db.commit()

	if request.method == "GET":
		# Ler dados do aluno com id na base de dados
		if all=='all':
			if tipo == None:
				try:
					cursor = g.db.execute('SELECT * FROM turma')
					todos=map(lambda x: {'id': x[0], 'id_disciplina': x[1], 'tipo': x[2], 'designacao': x[3]},cursor.fetchall())
					r = make_response(json.dumps(todos))
					r.status_code = 200
				except:
					r = make_response()
					r.status_code = 500
			elif tipo=='disciplina':
				try:
					cursor = g.db.execute('SELECT id From turma WHERE id=%d' % id)
					registos = cursor.fetchone()
					if registos!=None:
						cursor = g.db.execute('SELECT * FROM turma WHERE id_disciplina=%s' %id)
						todos=map(lambda x: {'id': x[0], 'id_disciplina': x[1], 'tipo': x[2], 'designacao': x[3]},cursor.fetchall())
						r = make_response(json.dumps(todos))
						r.status_code = 200
					else:
						r = make_response()
						r.status_code = 204
				except:
					r = make_response()
					r.status_code = 500
		else:
			try:
				cursor = g.db.execute('SELECT * FROM turma WHERE id=%d' % id) # Fazer query e obter todos
				todos=map(lambda x: {'id': x[0], 'id_disciplina': x[1], 'tipo': x[2], 'designacao': x[3]},cursor.fetchall())
				r = make_response(json.dumps(todos))
				r.status_code = 200
			except:
				r = make_response()
				r.status_code = 500
		#todos = cursor.fetchall() # os resultados
		#print todos
		
		#todos = cursor.fetchall() # os resultados
		

	if request.method == "PUT":
		try:
			data = json.loads(request.data)
			g.db.execute('INSERT INTO turma VALUES (NULL, ?, ?,?)', (data['id_disciplina'],data['tipo'], data['designacao']))
			g.db.commit()
			cursor = g.db.execute('SELECT id FROM turma WHERE id_disciplina=? AND tipo=? AND designacao=?', (data['id_disciplina'],data['tipo'], data['designacao']))
			id = cursor.fetchone()
			# Ler dados do aluno no pedido e inserir na base de dados
			# Em caso de sucesso responder com a localização do novo recurso
			r = make_response()
			r.status_code = 201
			r.headers['location'] = '/turmas/'+str(id[0])
		except:
			r = make_response()
			r.status_code = 500

	if request.method == "DELETE":
		if all=='all':
			if tipo == None:
				# g.db.execute('DELETE FROM inscricoes')
				# g.db.commit()
				try:
					cursor = g.db.execute('SELECT id From turma')
					registos = cursor.fetchone()
					if registos!=None:
						g.db.execute('DELETE FROM turma')
						g.db.commit()
						r = make_response()
						r.status_code = 200
					else:
						r = make_response()
						r.status_code = 204
				except:
					r = make_response()
					r.status_code = 500

			elif tipo=='disciplina':
				try:
					cursor = g.db.execute('SELECT id From disciplina')
					registos = cursor.fetchone()
					if registos!=None:
						g.db.execute('DELETE FROM turma where id_disciplina=%s' % id)
						g.db.commit()
						r = make_response()
						r.status_code = 200
					else:
						r = make_response()
						r.status_code = 204

				except:
					r = make_response()
					r.status_code = 500


		else:
			# g.db.execute('DELETE FROM inscricoes WHERE id_turma=%d' % id)
			# g.db.commit()
			try:
				cursor = g.db.execute('SELECT id From turma WHERE id=%d' % id)
				registos = cursor.fetchone()
				if registos!=None:
					g.db.execute('DELETE FROM turma WHERE id=%d' % id)
					g.db.commit()
					r = make_response()
					r.status_code = 200
				else:
					r = make_response()
					r.status_code = 204

			except:
				r = make_response()
				r.status_code = 500
	 	
	return r


if __name__ == '__main__':
	app.run(debug = True) 

