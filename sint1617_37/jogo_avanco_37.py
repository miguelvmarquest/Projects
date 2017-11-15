"""
Trabalho de Sistemas Inteligentes
Realizado por :
    Miguel Marques, nº47139
    Duarte Sousa, nº47164
    
"""

from Game import *

from copy import deepcopy

class JogoAvanco(Game) :
    """Representação para o jogo:
    """

    @staticmethod
    def outro_jogador(j) :
        return 'brancas' if j == 'pretas' else 'pretas'
    
    def __init__(self) :
        self.jogadores = ('brancas','pretas')
        self.sentido = {'brancas':-1,'pretas':1}        
        
        self.linhas = 7 # número de linhas
        self.cols = 7   # número de colunas
        self.objectivo = {'brancas':1,'pretas':self.linhas}
        tabuleiro_inicial = {'brancas':list(map(lambda y: (7,y), range(1,8)))+list(map(lambda y: (6,y), range(1,8))),'pretas':list(map(lambda y: (1,y), range(1,8)))+list(map(lambda y: (2,y), range(1,8)))}
        movs_possiveis = self.movimentos_possiveis(tabuleiro_inicial,self.jogadores[0])
        self.initial = GameState(
            to_move = self.jogadores[0],
            utility = 0,
            board = tabuleiro_inicial,
            moves = movs_possiveis)
    
    def movimentos_possiveis(self,tabuleiro,jogador) :
        """Três tipos de movimentos:
        - avança - ('avança',(x,y)) - avança a peça (x,y)
        - come-esq - ('come-esq',(x,y)) - peça (x,y) come à esquerda
        - come-dir - ('come-dir',(x,y)) - peça (x,y) come à direita
        """
        def frente_livre(tab,peca,jog) :
            pecas_todas = tab['brancas']+tabuleiro['pretas']
            x = peca[0]+self.sentido[jog]
            y = peca[1]
            return (1 <= x <= self.linhas) and (x,y) not in pecas_todas
        def frente_esq(tab,peca,jog) :
            pecas_todas = tab['brancas']+tabuleiro['pretas']
            x = peca[0]+self.sentido[jog]
            y = peca[1]+self.sentido[jog]
            return (1 <= x <= self.linhas) and (1 <= y <= self.linhas) and (x,y) not in pecas_todas
        def frente_dir(tab,peca,jog) :
            pecas_todas = tab['brancas']+tabuleiro['pretas']
            x = peca[0]+self.sentido[jog]
            y = peca[1]-self.sentido[jog]
            return (1 <= x <= self.linhas) and (1 <= y <= self.linhas)and (x,y) not in pecas_todas    
        def pode_comer_esq(tab,peca,jog) :
            x = peca[0]+self.sentido[jog]
            y = peca[1]+self.sentido[jog]
            return (x,y) in tab[JogoAvanco.outro_jogador(jog)]
        def pode_comer_dir(tab,peca,jog) :
            x = peca[0]+self.sentido[jog]
            y = peca[1]-self.sentido[jog]
            return (x,y) in tab[JogoAvanco.outro_jogador(jog)]
        
        pecas = tabuleiro[jogador]
        movs = list()
        for p in pecas :
            if frente_livre(tabuleiro,p,jogador) :
                movs.append(("avança",p))
            if frente_esq(tabuleiro,p,jogador) :
                movs.append(("avançaEsq",p))
            if frente_dir(tabuleiro,p,jogador) :
                movs.append(("avançaDir",p))
            if pode_comer_esq(tabuleiro,p,jogador) :
                movs.append(("come-esq",p))
            if pode_comer_dir(tabuleiro,p,jogador) :
                movs.append(("come-dir",p))
        return movs
        
        
    def actions(self,state) :
        return state.moves
    
    def result(self,state,move) :
        """
        Requires: 'move' é uma jogada válida no estado dado ('state')
        """
        accao,peca = move
        jogador = state.to_move
        adversario = JogoAvanco.outro_jogador(jogador)
        tabuleiro = deepcopy(state.board)
        tabuleiro[jogador].remove(peca)
        if accao == 'avança' :
            x = peca[0]+self.sentido[jogador]
            y = peca[1]
            tabuleiro[jogador].append((x,y))
        elif accao == 'avançaEsq' :
            x = peca[0]+self.sentido[jogador]
            y = peca[1]+self.sentido[jogador]
            tabuleiro[jogador].append((x,y))
        elif accao == 'avançaDir' :
            x = peca[0]+self.sentido[jogador]
            y = peca[1]-self.sentido[jogador]
            tabuleiro[jogador].append((x,y))
        elif accao == 'come-esq' :
            x = peca[0]+self.sentido[jogador]
            y = peca[1]+self.sentido[jogador]
            tabuleiro[jogador].append((x,y))
            tabuleiro[adversario].remove((x,y))
        else : # come-dir
            x = peca[0]+self.sentido[jogador]
            y = peca[1]-self.sentido[jogador]
            tabuleiro[jogador].append((x,y))
            tabuleiro[adversario].remove((x,y))
        estado = GameState(to_move = JogoAvanco.outro_jogador(jogador),
                           board = tabuleiro,
                           moves = self.movimentos_possiveis(tabuleiro,JogoAvanco.outro_jogador(jogador)),
                           utility = self.calcular_utilidade(tabuleiro,jogador))
        return estado

    
    def calcular_utilidade(self,tabuleiro,jogador) :
        def objectivo(linha,jogador) :
            return linha in [x for (x,_) in tabuleiro[jogador]]
        
        utilidade = 0
        adversario = JogoAvanco.outro_jogador(jogador)
        if objectivo(self.objectivo[jogador],jogador) \
           or tabuleiro[adversario] == []:
            utilidade = 1
        elif objectivo(self.objectivo[adversario],adversario) \
             or tabuleiro[jogador] == []:
            utilidade = -1
        
        return utilidade
    
    def utility(self, state, player):
        return self.calcular_utilidade(state.board,player)
    
    def terminal_test(self,state) :
        return any([self.utility(state,x) != 0 for x in self.jogadores])

    def display(self, state):
        board = state.board
        print("Tabuleiro actual:")
        for x in range(0, self.linhas + 1):
            if x==0:
                print('  ', end=' ')
            else:
                print('\033[4m'+str(x)+'\033[0m', end='|')
            for y in range(1, self.cols + 1):
                if x==0:
                    if y==1:
                        print('', end=' ')
                    print('\033[4m'+str(y)+'\033[0m', end='|')
                    
                else:
                    if y==1:
                        print(' ', end='|')
                    if (x,y) in board['brancas'] :
                        print('\033[4mO\033[0m', end='|')
                    elif (x,y) in board['pretas'] :
                        print('\033[4m*\033[0m', end='|')
                    else :
                        print('\033[4m \033[0m',end='|')

                    
            
            if x==0:
                print()
                print("   _______________")
            else:
                print()
        if self.terminal_test(state) :
            print("FIM do Jogo")
            print("-----------------------")
            print()
        else :
            print("Próximo jogador:{}\n".format(state.to_move))
            print("-----------------------")
            print()



def query_player(game, state, nivel = 0):
    "Make a move by querying standard input."

    for i in range(0,len(game.actions(state)),4):
        
        print(str(i)+":"+str(game.actions(state)[i]), end='  |  ')
        if i+1<len(game.actions(state)):
            print(str(i+1)+":"+str(game.actions(state)[i+1]), end='  |  ')
        if i+2<len(game.actions(state)):
            print(str(i+2)+":"+str(game.actions(state)[i+2]), end='  |  ')
        if i+3<len(game.actions(state)):
            print(str(i+3)+":"+str(game.actions(state)[i+3]))

    print()
    print()
    move_string = input('Choose the number of the move that you want? ')
    try:
        flag=True
        flag2=False
        while flag:
            if flag2 :
                move_string2 = input('Illegal input please try again: ')
                move_string = None
            else:
                move_string2 = move_string
                flag2 = True
            if move_string2.isdigit():
                move = eval(move_string2)
            else:
                move=-1
            
            print ()

            if -1 < move < len(game.actions(state)):
                flag=False


    except NameError:
        move = move_string
    return game.actions(state)[move]
