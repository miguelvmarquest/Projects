#!/usr/bin/python
# -*- coding: utf-8 -*-
"""
Aplicações distribuídas - Projeto 1 - lock_server.py
Grupo: 6
Números de aluno: 46598, 46602, 47139 
"""

# Zona para fazer importação
import socket as s
import time
import sys
from sock_utils import create_tcp_server_socket, unmarshal, marshal_send
import pickle
import struct

###############################################################################

class resource_lock:

    def __init__(self):
        """
        Define e inicializa as características de um LOCK num recurso.
        """
        self.blocked = False
        self.times_blocked = 0
        self.blocker = ""
        self.block_time = ""

    def lock(self, client_id, time_limit):
        """
        Bloqueia o recurso se este não estiver bloqueado ou mantém o bloqueio
        se o recurso estiver bloqueado pelo cliente client_id. Neste caso renova
        o bloqueio do recurso até time_limit.
        Retorna True se bloqueou o recurso ou False caso contrário.
        """
        if not self.blocked:
            self.blocked =True
            self.times_blocked += 1
            self.blocker = client_id
            self.block_time = time_limit    
            return True
        elif self.blocked and client_id == self.blocker:
            self.times_blocked +=  1
            self.block_time = time_limit
            return True
        else:
            return False     

    def urelease(self):
        """
        Liberta o recurso incondicionalmente, alterando os valores associados
        ao bloqueio.
        """
        self.blocked = False
        self.blocker = ""
        self.block_time = ""
        
    def release(self, client_id):
        """
        Liberta o recurso se este foi bloqueado pelo cliente client_id,
        retornando True nesse caso. Caso contrário retorna False.
        """
        if self.blocked and self.blocker == client_id:
            self.blocked = False
            self.blocker = ""
            self.block_time = ""
            return True
        return False

    def test(self):
        """
        Retorna o estado de bloqueio do recurso.
        """
        return self.blocked
    
    def stat(self):
        """
        Retorna o número de vezes que este recurso já foi bloqueado.
        """
        return self.times_blocked
        
###############################################################################

class lock_pool:
    def __init__(self, N):
        """
        Define um array com um conjunto de locks para N recursos. Os locks podem
        ser manipulados pelos métodos desta classe.
        """
        self.array = [resource_lock() for x in range(N)]
        
    def clear_expired_locks(self):
        """
        Verifica se os recursos que estão bloqueados ainda estão dentro do tempo
        de concessão do bloqueio. Liberta os recursos caso o seu tempo de
        concessão tenha expirado.
        """
        for i in self.array:
            if i.blocked and i.block_time < time.time():
                i.urelease()

    def lock(self, resource_id, client_id, time_limit):
        """
        Tenta bloquear o recurso resource_id pelo cliente client_id, até ao
        instante time_limit. Retorna True em caso de sucesso e False caso
        contrário.
        """
        return self.array[resource_id].lock(client_id, time_limit)

    def release(self, resource_id, client_id):
        """
        Tenta libertar o recurso resource_id pelo cliente client_id. Retorna
        True em caso de sucesso e False caso contrário.
        """
        return self.array[resource_id].release(client_id)

    def test(self,resource_id):
        """
        Retorna True se o recurso resource_id estiver bloqueado e False caso
        contrário.
        """
        return self.array[resource_id].test()

    def stat(self,resource_id):
        """
        Retorna o número de vezes que o recurso resource_id já foi bloqueado.
        """
        return str(self.array[resource_id].stat())
    def __repr__(self):
        """
        Representação da classe para a saída standard. A string devolvida por
        esta função é usada, por exemplo, se uma instância da classe for
        passada à função print.
        """
        output = ""
        #
        # Acrecentar a output uma linha por cada recurso bloqueado, da forma:
        # recurso <número do recurso> bloqueado pelo cliente <id do cliente> até
        # <instante limite da concessão do bloqueio>
        #
        # Caso o recurso não esteja bloqueado a linha é simplesmente da forma:
        # recurso <número do recurso> desbloqueado
        #
        for i in range(len(self.array)):
			if self.array[i].blocked:
				output += "recurso " + str(i) + " bloqueado pelo cliente " + str(self.array[i].blocker) + " até " + str(time.ctime(self.array[i].block_time)) + "\n"
			else:
				output += "recurso " + str(i) + " desbloqueado" + "\n"
        return output

###############################################################################

# código do programa principal
HOST = ''
try:
    PORT = int(sys.argv[1])
except ValueError:
    print "Número de porta inválido"
    sys.exit(0)

try:
    N = int(sys.argv[2])
except ValueError:
    print "Número de recursos inválido"
    sys.exit(0)

try:
    TIME = int(sys.argv[3])
except ValueError:
    print "Tempo de concessão inválido"
    sys.exit(0)

lock_pool = lock_pool(N)
try:
    socket = create_tcp_server_socket(HOST, PORT, 1)
except s.error:
    print "Conexão impossível. Porta em uso..."
    sys.exit(0)
while True:
    response = []
    try:
        (conn_sock, addr) = socket.accept()
    except s.error:
        print "Erro na conexão"
        sys.exit(0)
    print "Ligado a %s no porto %s" % (addr, PORT)
    lock_pool.clear_expired_locks()
    msg = unmarshal(conn_sock)[0].split(" ")
    time_limit = time.time() + TIME 
    try:  
        if msg[0].upper() == "LOCK":
            if len(msg)==2:
                response.append("UNKNWON RESOURCE") 
            else:
                client_id = int(msg[2])
                try:
                    resource_id = int(msg[1])
                    if resource_id > N or resource_id < 0:
                        response.append("UNKNWON RESOURCE")
                    elif lock_pool.lock(resource_id, client_id, time_limit):
                        response.append("OK")
                    else:
                        response.append("NOK")
                except:
                    response.append("UNKNWON RESOURCE")
        elif msg[0].upper() == "RELEASE":
            if len(msg)==2:
                response.append("UNKNWON RESOURCE")
            else:
                client_id = int(msg[2])
                try:
                    resource_id = int(msg[1])
                    if resource_id > N or resource_id < 0:
                        response.append("UNKNWON RESOURCE")
                    elif lock_pool.release(resource_id, client_id):
                        response.append("OK")
                    else:
                        response.append("NOK")
                except:
                    response.append("UNKNWON RESOURCE")
        elif msg[0].upper() == "TEST":
            try:
                resource_id = int(msg[1])
                if resource_id > N or resource_id < 0:
                    response.append("UNKNWON RESOURCE")
                else:
                    response.append("LOCKED" if lock_pool.test(resource_id) else "UNLOCKED")
            except:
                response.append("UNKNWON RESOURCE")
        elif msg[0].upper() == "STAT":
            try:
                resource_id = int(msg[1])
                if resource_id > N or resource_id < 0:
                    response.append("UNKNWON RESOURCE")
                else:
                    response.append(lock_pool.stat(resource_id))
            except:
                response.append("UNKNWON RESOURCE")
        else:
            response.append("UNKNWON COMMAND")
    except:
        response.append("UNKNWON COMMAND")
    marshal_send(conn_sock, response)
    conn_sock.close()
    print lock_pool
