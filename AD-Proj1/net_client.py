# -*- coding: utf-8 -*-
"""
Aplicações distribuídas - Projeto 1 - net_client.py
Grupo: 6
Números de aluno: 46598, 46602, 47139
"""

# zona para fazer importação

from sock_utils import create_tcp_client_socket, receive_all, marshal_send, unmarshal
import socket
import pickle
# definição da classe server 

class server:
    """
    Classe para abstrair uma ligação a um servidor TCP. Implementa métodos
    para estabelecer a ligação, para envio de um comando e receção da resposta,
    e para terminar a ligação
    """
    def __init__(self, address, port):
        """
        Inicializa a classe com parâmetros para funcionamento futuro.
        """
        self.address = address
        self.port = port
        self.socket = ""

        
    def connect(self):
        """
        Estabelece a ligação ao servidor especificado na inicialização do
        objeto.
        """
        self.socket = create_tcp_client_socket(self.address, self.port)
        

    def send_receive(self, data):
        """
        Envia os dados contidos em data para a socket da ligação, e retorna a
        resposta recebida pela mesma socket.
        """
        marshal_send(self.socket, data)
        msg = unmarshal(self.socket)
        return msg
        
    
    def close(self):
        """
        Termina a ligação ao servidor.
        """
        self.socket.close()

