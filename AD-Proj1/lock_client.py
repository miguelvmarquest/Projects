#!/usr/bin/python
# -*- coding: utf-8 -*-
"""
Aplicações distribuídas - Projeto 1 - lock_client.py
Grupo: 6
Números de aluno: 46598, 46602, 47139
"""
# Zona para fazer imports

import net_client as nt
import socket as s
import sys
# Programa principal

exit = True
HOST = sys.argv[1]
try:
    PORT = int(sys.argv[2])
except ValueError:
    print "Número de porta inválido"
    sys.exit(0)

ID = sys.argv[3]
socket = nt.server(HOST, PORT)
while exit:
	msg = raw_input("comando > ")
	if len(msg) > 0:
		if  msg == "EXIT" or msg == "exit":
			sys.exit(0)
		try:
			socket.connect()
		except s.error:
			print "Impossível de ligar. Ligação recusada"
			sys.exit()
		msg = msg + " " + str(ID)
		recv = socket.send_receive([msg])
		if recv != None or recv != "None":
			print recv[0]	
		socket.close()