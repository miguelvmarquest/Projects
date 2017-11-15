#!/usr/bin/env python
# -*- coding: utf-8 -*-
"""
Aplicações Distribuídas - Projeto 4 - cliente.py
Grupo: 6
Números de aluno: Francisco Mendonça 46602, Miguel Marques 47139, Susana Vieira 46598
"""

import requests, json

def structure(dic):
	for i in dic.keys():
		print i+ ':' , dic[i]

def checkCommand(stri):
	lis = stri.split()

	###############-----ADD-------####################


	if lis[0].upper() == 'ADD':
		if len(lis)==3:
			try:
				r= s.put('https://localhost:5000/alunos/inscricao/'+lis[1]+'/'+lis[2], verify='root.pem', cert=('cliente.crt', 'cliente.key'))
				if r.status_code == 201:
					print 'Status Code:',r.status_code, '(Created)'
					structure(r.headers)
				else:
					print 'Status Code:',r.status_code, '(Internal Server Error)'
					structure(r.headers)
					print 
					print 'Os parâmetros que colocou não são válidos'
				
			except:
				print 'Os parâmetros que colocou não são válidos'
					
		elif lis[1].upper() == 'ALUNO':
			if True:
				dados = {'nome': lis[4],'nacionalidade': lis[2], 'idade': int(lis[3])}
				r = s.put('https://localhost:5000/alunos', json = dados, verify='root.pem', cert=('cliente.crt', 'cliente.key'))
				
				if r.status_code == 201:
					print 'Status Code:',r.status_code, '(Created)'
				else:
					print 'Status Code:',r.status_code
				structure(r.headers)

			else:
				print 'Os parâmetros que colocou não são válidos'
		

		elif lis[1].upper() == 'DISCIPLINA':
			try:
				dados = {'ano': int(lis[2]), 'semestre': int(lis[3]), 'designacao': lis[4]}
				r = s.put('https://localhost:5000/disciplinas', json = dados, verify='root.pem', cert=('cliente.crt', 'cliente.key'))
				if r.status_code == 201:
					print 'Status Code:',r.status_code, '(Created)'
				else:
					print 'Status Code:',r.status_code
				structure(r.headers)
			except:
				print 'Os parâmetros que colocou não são válidos'
		

		elif lis[1].upper() == 'TURMA':
			try:
				dados = {'id_disciplina': int(lis[2]),'tipo': lis[3], 'designacao': lis[4]}
				r = s.put('https://localhost:5000/turmas', json = dados, verify='root.pem', cert=('cliente.crt', 'cliente.key'))
				
				if r.status_code == 201:
					print 'Status Code:',r.status_code, '(Created)'
					structure(r.headers)

				elif r.status_code == 500:
					print 'Status Code:',r.status_code, '(Internal Server Error)'
					structure(r.headers)
					print 
					print 'Os parâmetros que colocou não são válidos'

			except:
				print 'Os parâmetros que colocou não são válidos'
		

		else:
			print 'Os parâmetros que colocou não são válidos'

	


	###############-----SHOW-------####################


	elif lis[0].upper() == 'SHOW':
		

		if lis[1].upper() == 'ALL':

			if lis[2].upper() == 'ALUNOS':
				if len(lis)==3:
					r = s.get('https://localhost:5000/alunos/all', verify='root.pem', cert=('cliente.crt', 'cliente.key')) 
					cont = json.loads(r.content)
					if r.status_code == 200:
						print 'Status Code:',r.status_code, '(Ok)'
					else:
						print 'Status Code:',r.status_code
					structure(r.headers)
					print 
					if len(cont)>0:
						for i in range(len(cont)):
							print 'Id: '+str(cont[i]['id'])+ ' | Nome: ' + cont[i]['nome'] + ' | Nacionalidade: ' + cont[i]['nacionalidade'] + ' | Idade: '+ str(cont[i]['idade'])
					else:
						print 'Não existem alunos'
					return
				elif lis[3].upper() == 'DISCIPLINA':
					try:
						r = s.get('https://localhost:5000/alunos/all/disciplina/%s' % lis[4], verify='root.pem', cert=('cliente.crt', 'cliente.key'))
						
						if r.status_code == 200:
							cont = json.loads(r.content)
							print 'Status Code:',r.status_code, '(Ok)'
						elif r.status_code == 204:
							print 'Status Code:',r.status_code, '(No Content)'	
						else:
							print 'Status Code:',r.status_code
						structure(r.headers)
						print
						if len(cont)>0:
							for i in range(len(cont)):
								print 'Id: '+str(cont[i]['id'])+ ' | Nome: ' + cont[i]['nome'] + ' | Nacionalidade: ' + cont[i]['nacionalidade'] + ' | Idade: '+ str(cont[i]['idade'])
						else:
							print 'Não existem alunos nessa disciplina'
						return
					except:
						print 'Os parâmetros que colocou não são válidos'

				elif lis[3].upper() == 'TURMA':
					try:
						r = s.get('https://localhost:5000/alunos/all/turma/%s' % lis[4], verify='root.pem', cert=('cliente.crt', 'cliente.key'))
						
						if r.status_code == 200:
							cont = json.loads(r.content)
							print 'Status Code:',r.status_code, '(Ok)'
						elif r.status_code == 204:
							print 'Status Code:',r.status_code, '(No Content)'
						else:
							print 'Status Code:',r.status_code
						structure(r.headers)
						print
						if len(cont)>0:
							for i in range(len(cont)):
								print 'Id: '+str(cont[i]['id'])+ ' | Nome: ' + cont[i]['nome'] + ' | Nacionalidade: ' + cont[i]['nacionalidade'] + ' | Idade: '+ str(cont[i]['idade'])
						else:
							print 'Não existem alunos nessa turma'
						return
					except:
						print 'Os parâmetros que colocou não são válidos'

				else: 
					print 'Parametros não são válidos'

				


			elif lis[2].upper() == 'DISCIPLINAS':
				r = s.get('https://localhost:5000/disciplinas/all', verify='root.pem', cert=('cliente.crt', 'cliente.key')) 
				cont = json.loads(r.content)
				if r.status_code == 200:
					print 'Status Code:',r.status_code, '(Ok)'
				else:
					print 'Status Code:',r.status_code
				structure(r.headers)
				print 
				if len(cont)>0:
					for i in range(len(cont)):
						print 'Id: '+str(cont[i]['id'])+ ' | Designação: ' + str(cont[i]['designacao']) + ' | Ano: ' + str(cont[i]['ano']) + ' | Semestre: '+ str(cont[i]['semestre'])
				else:
					print 'Não existem disciplinas'
				return 
			

			elif lis[2].upper() == 'TURMAS':

				if len(lis)==3:	
					r = s.get('https://localhost:5000/turmas/all', verify='root.pem', cert=('cliente.crt', 'cliente.key')) 
					cont = json.loads(r.content)
					if r.status_code == 200:
						print 'Status Code:',r.status_code, '(Ok)'
					else:
						print 'Status Code:',r.status_code
					structure(r.headers)
					print 
					if len(cont)>0:
						for i in range(len(cont)):
							#print cont[i]
							print 'Id: '+str(cont[i]['id'])+ ' | Id Disciplina: ' + str(cont[i]['id_disciplina']) + ' | Tipo: ' + cont[i]['tipo'] + u' | Designação: '+ str(cont[i]['designacao'])
					else:
						print 'Não existem turmas'
					return
				

				elif lis[3].upper() == 'DISCIPLINA':
					try:
						tentar = int(lis[4])
						r = s.get('https://localhost:5000/turmas/all/disciplina/%s' % lis[4], verify='root.pem', cert=('cliente.crt', 'cliente.key')) 
						
						if r.status_code == 200:
							cont = json.loads(r.content)
							print 'Status Code:',r.status_code, '(Ok)'
						elif r.status_code == 204:
							print 'Status Code:',r.status_code, '(No Content)'
						else:
							print 'Status Code:',r.status_code
						structure(r.headers)
						print
						if len(cont)>0:
							for i in range(len(cont)):
								#print cont[i]
								print 'Id: '+str(cont[i]['id'])+ ' | Id Disciplina: ' + str(cont[i]['id_disciplina']) + ' | Tipo: ' + cont[i]['tipo'] + u' | Designação: '+ str(cont[i]['designacao'])
						else:
							print 'Não existem turmas'
						return
					except:
						print 'Parametros não são válidos'
				else:
					print 'Colocou parametros que não são válidos'
				

			
			else:
				print 'Colocou parametros que não são válidos'
		

		elif lis[1].upper() == 'ALUNO':

			try:
				r = s.get('https://localhost:5000/alunos/%s' % lis[2], verify='root.pem', cert=('cliente.crt', 'cliente.key')) 
				cont = json.loads(r.content)
				if r.status_code == 200:
					print 'Status Code:',r.status_code, '(Ok)'
				else:
					print 'Status Code:',r.status_code
				structure(r.headers)
				print 
				if len(cont)>0:
					for i in range(len(cont)):
						print 'Id: '+str(cont[i]['id'])+ ' | Nome: ' + cont[i]['nome'] + ' | Nacionalidade: ' + cont[i]['nacionalidade'] + ' | Idade: '+ str(cont[i]['idade'])
				else:
					print 'Não existem alunos'
				return 
			#como esta em cima é sºo para os casos de ALL
			except:
				print 'Não existem turmas ou parametros não são válidos'
				return
		

		elif lis[1].upper()== 'DISCIPLINA':
			try:
				r = s.get('https://localhost:5000/disciplinas/%s' % lis[2], verify='root.pem', cert=('cliente.crt', 'cliente.key')) 
				cont = json.loads(r.content)
				if r.status_code == 200:
					print 'Status Code:',r.status_code, '(Ok)'
				else:
					print 'Status Code:',r.status_code
				structure(r.headers)
				print
				if len(cont)>0:
					for i in range(len(cont)):
						print 'Id: '+str(cont[i]['id'])+ u' | Designação: ' + cont[i]['designacao'] + ' | Ano: ' + str(cont[i]['ano']) + ' | Semestre: '+ str(cont[i]['semestre'])
				else:
					print 'Não existem disciplinas'
				return 
			except:
				print 'Não existem disciplinas ou parametros não são válidos'
				return
		

		elif lis[1].upper() == 'TURMA':
			try:
				r = s.get('https://localhost:5000/turmas/%s' % lis[2], verify='root.pem', cert=('cliente.crt', 'cliente.key')) 
				cont = json.loads(r.content)
				if r.status_code == 200:
					print 'Status Code:',r.status_code, '(Ok)'
				else:
					print 'Status Code:',r.status_code
				structure(r.headers)
				print
				if len(cont)>0:
					for i in range(len(cont)):
						print 'Id: '+str(cont[i]['id'])+ ' | Id Disciplina: ' + str(cont[i]['id_disciplina']) + ' | Tipo: ' + str(cont[i]['tipo']) + u' | Designação: '+ str(cont[i]['designacao'])
				else:
					print 'Não existem turmas'
				return 
			except:
				print 'Não existem turmas ou parametros não são válidos'
				return

		elif len(lis)==3:
			try:
				r = s.get('https://localhost:5000/alunos/inscricao/'+lis[1]+'/'+lis[2], verify='root.pem', cert=('cliente.crt', 'cliente.key')) 
				cont = json.loads(r.content)
				if r.status_code == 200:
					print 'Status Code:',r.status_code, '(Ok)'
				else:
					print 'Status Code:',r.status_code
				structure(r.headers)
				print
				if len(cont)>0:
					for i in range(len(cont)):
						print 'Id Aluno: '+str(cont[i]['id_aluno'])+ ' | Id_turma: ' + str(cont[i]['id_turma']) + ' | Ano-letivo: ' + cont[i]['ano_letivo']
				else:
					print 'Não existe essa inscrição'
				return 
			#como esta em cima é so para os casos de ALL
			except:
				print 'Não existem alunos ou parametros nnão são válidos'
				return
		

		else:
			print 'Colocou parametros que não são válidos'

	

	###############-----REMOVE-------####################


	elif lis[0].upper() == 'REMOVE':
		

		if lis[1].upper() == 'ALL':
			if lis[2].upper() == 'ALUNOS':
				if len(lis)==3:
					try:
						r = s.delete('https://localhost:5000/alunos/all', verify='root.pem', cert=('cliente.crt', 'cliente.key'))
						if r.status_code == 200:
							print 'Status Code:',r.status_code, '(Ok)'
						elif r.status_code == 204:
							print 'Status Code:',r.status_code, '(No Content)'
						else:
							print 'Status Code:',r.status_code
						structure(r.headers)

					except:
						print 'O aluno que está a tentar apagar não existe ou então colocou parametros que não são válidos'

				elif lis[3].upper() == 'DISCIPLINA':
					try:
						r = s.delete('https://localhost:5000/alunos/all/disciplina/'+lis[4], verify='root.pem', cert=('cliente.crt', 'cliente.key'))
						if r.status_code == 200:
							print 'Status Code:',r.status_code, '(Ok)'
						elif r.status_code == 204:
							print 'Status Code:',r.status_code, '(No Content)'
						else:
							print 'Status Code:',r.status_code
						structure(r.headers)

					except:
						print 'O aluno que está a tentar apagar não existe ou então colocou parametros que não são válidos'
				elif lis[3].upper() == 'TURMA':
					try:
						r = s.delete('https://localhost:5000/alunos/all/turma/'+lis[4], verify='root.pem', cert=('cliente.crt', 'cliente.key'))
						if r.status_code == 200:
							print 'Status Code:',r.status_code, '(Ok)'
						elif r.status_code == 204:
							print 'Status Code:',r.status_code, '(No Content)'
						else:
							print 'Status Code:',r.status_code
						structure(r.headers)

					except:
						print 'O aluno que está a tentar apagar não existe ou então colocou parametros que não são válidos'
				else:
					print 'Colocou parametros que não são válidos'


			elif lis[2].upper() == 'DISCIPLINAS':
				try:
					r = s.delete('https://localhost:5000/disciplinas/all', verify='root.pem', cert=('cliente.crt', 'cliente.key'))
					if r.status_code == 200:
						print 'Status Code:',r.status_code, '(Ok)'
					elif r.status_code == 204:
						print 'Status Code:',r.status_code, '(No Content)'
					else:
						print 'Status Code:',r.status_code
					structure(r.headers)

				except:
					print 'Colocou parametros que não são válidos'

			elif lis[2].upper() == 'TURMAS':
				if len(lis)==3:
					try:
						r = s.delete('https://localhost:5000/turmas/all', verify='root.pem', cert=('cliente.crt', 'cliente.key'))
						if r.status_code == 200:
							print 'Status Code:',r.status_code, '(Ok)'
						elif r.status_code == 204:
							print 'Status Code:',r.status_code, '(No Content)'
						else:
							print 'Status Code:',r.status_code
						structure(r.headers)

					except:
						print 'Colocou parametros que não são válidos'
				elif lis[3].upper() == 'DISCIPLINA':
					try:
						r = s.delete('https://localhost:5000/turmas/all/disciplina/%s' % lis[4], verify='root.pem', cert=('cliente.crt', 'cliente.key'))
						if r.status_code == 200:
							print 'Status Code:',r.status_code, '(Ok)'
						elif r.status_code == 204:
							print 'Status Code:',r.status_code, '(No Content)'
						else:
							print 'Status Code:',r.status_code
						structure(r.headers)

					except:
						print 'Colocou parametros que não são válidos'

			else:
				print 'Colocou parametros que não são válidos'
		

		elif lis[1].upper() == 'ALUNO':
			try:
				r = s.delete('https://localhost:5000/alunos/' + lis[2], verify='root.pem', cert=('cliente.crt', 'cliente.key'))
				if r.status_code == 200:
					print 'Status Code:',r.status_code, '(Ok)'
				elif r.status_code == 204:
					print 'Status Code:',r.status_code, '(No Content)'
				else:
					print 'Status Code:',r.status_code
				structure(r.headers)

			except:
				print 'O aluno que está a tentar apagar não existe ou então colocou parametros que não são válidos'
		

		elif lis[1].upper() == 'DISCIPLINA':
			try:
				r = s.delete('https://localhost:5000/disciplinas/' + lis[2], verify='root.pem', cert=('cliente.crt', 'cliente.key'))
				if r.status_code == 200:
					print 'Status Code:',r.status_code, '(Ok)'
				elif r.status_code == 204:
					print 'Status Code:',r.status_code, '(No Content)'
				else:
					print 'Status Code:',r.status_code
				structure(r.headers)

			except:
				print 'A disciplina que está a tentar apagar não existe ou então colocou parametros que não são válidos'
		

		elif lis[1].upper() == 'TURMA':
			try:
				r = s.delete('https://localhost:5000/turmas/' + lis[2], verify='root.pem', cert=('cliente.crt', 'cliente.key'))
				if r.status_code == 200:
					print 'Status Code:',r.status_code, '(Ok)'
				elif r.status_code == 204:
					print 'Status Code:',r.status_code, '(No Content)'
				else:
					print 'Status Code:',r.status_code
				structure(r.headers)

			except:
				print 'A turma que está a tentar apagar não existe ou então colocou parametros que não são válidos'

		elif len(lis) == 3:
			try:
				r = s.delete('https://localhost:5000/alunos/inscricao/'+lis[1]+'/'+lis[2], verify='root.pem', cert=('cliente.crt', 'cliente.key'))
				if r.status_code == 200:
					print 'Status Code:',r.status_code, '(Ok)'
				elif r.status_code == 204:
					print 'Status Code:',r.status_code, '(No Content)'
				else:
					print 'Status Code:',r.status_code
				structure(r.headers)

			except:
				print 'A inscrição que está a tentar apagar não existe ou então colocou parametros que não são válidos'

		else:
			print 'Colocou parametros que não são válidos'

	else:
			print 'Colocou parametros que não são válidos'




exit = True
s = requests.Session()
while exit:
	print
	print 'ADD | REMOVE | SHOW'
	msg = raw_input("comando > ")
	if len(msg) > 0:
		print 
		checkCommand(msg)
	else:
		print 'Tem que colocar parâmetros'



























