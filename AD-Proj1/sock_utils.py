# -*- coding: utf-8 -*-
"""
Aplicações distribuídas - Projeto 1 - sock_utils.py
Grupo: 6
Números de aluno: 46598, 46602, 47139
"""

import socket
import struct
import pickle

def create_tcp_server_socket(address, port, queue_size):
	s = socket.socket(socket.AF_INET, socket.SOCK_STREAM) #creates socket
	s.bind((address, port)) #binds socket to the address, and the port where the serve will atend new requests
	s.listen(queue_size) #listens for new requests
	return s

def create_tcp_client_socket(address, port):
	cl = socket.socket(socket.AF_INET, socket.SOCK_STREAM) #creates socket
	cl.connect((address, port))
	return cl #connects to socket

def receive_all(socket, length):
	return socket.recv(length) #receives from socket

def unmarshal(s):
	try:
		byte = s.recv(4)
	except socket.error:
		print "Impossível de receber do servidor..."
		s.close()
		return
	size_bytes = struct.unpack('!i', byte)[0]
	try:
		msg_bytes = s.recv(size_bytes)
	except socket.error:
		print "Impossível de receber do servidor..."
		s.close()
		return
	else:
		return pickle.loads(msg_bytes)

def marshal_send(s, data):
	msg_bytes = pickle.dumps(data, -1)
	size_bytes = struct.pack('!i', len(msg_bytes))
	try:
		s.sendall(size_bytes)
		s.sendall(msg_bytes)
	except socket.error:
		print "Impossível de enviar..."
		s.close()
