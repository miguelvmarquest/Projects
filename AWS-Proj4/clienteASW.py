#!/usr/bin/env python
# -*- coding: utf-8 -*-
"""
Aplicações e Serviços na Web - Projeto 4 - clienteASW.py
Grupo: 6
Números de aluno: Francisco Mendonça 46602, Miguel Marques 47139, Susana Vieira 46598
"""
import SOAPpy
server = SOAPpy.SOAPProxy("http://appserver.di.fc.ul.pt/~asw47139/proj2/ws_serv.php","uri:cumpwsdl")


flag=True;
while flag:
	inputText = raw_input("Escreva uma das funções seguintes com respectivos argumentos ValorAtualDoItem(Id) ou LicitaItem(Id,valor,username,password) ou exit : ")

	if inputText[:4].upper()=="EXIT":
		flag=False

	elif inputText[:16].upper()=="VALORATUALDOITEM":
		try:
			Id=inputText.replace(')','(').split('(')[1]
			print server.ValorAtualDoItem(Id)
		except:
			print "Introduziu mal o commando ou argumentos"

	elif inputText[:10].upper()=="LICITAITEM":
		try:
			args=inputText.replace(')','(').split('(')[1].split(',')
			print server.LicitaItem(args[0], args[1], args[2], args[3])
		except:
			print "Introduziu mal o commando ou argumentos"
	else:
		print "Introduziu mal o commando"

print "Programa terminado"
