from selenium import webdriver
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.chrome.options import Options
import time
import os
import requests

# Simula o User-Agent do navegador para evitar bloqueios
agent = {
    'User-Agent': 'Mozilla/5.0 (Windows NT 10.0;} Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3'
}
#  Classes do BOT

# OBSERVAÇÃO: As classes e os XPATH's podem mudar a qualquer momento por conta de atualizações na pagina web do whatsapp, entre outros motivos.
bolinha_notificacao = 'x173ssrc'
telefone_xpath = '//*[@id="main"]/header/div[2]/div/div/div/div/span'
msg_cliente = '_akbu'
caixa_msg = '//*[@id="main"]/footer/div[1]/div/span/div/div[2]/div/div[3]/div[1]/p'
usuario = ''





# Salva o perfil do bot

dir_path = os.getcwd()
sessao_path = os.path.join(dir_path, "sessao", "zap")


chrome_options = Options()
chrome_options.add_argument(f"user-data-dir={sessao_path}")

# Abre o WhatsApp Web com o perfil salvo

driver = webdriver.Chrome(options=chrome_options)
driver.get('https://web.whatsapp.com/')
time.sleep(10)

# Configuração do BOT

def bot():
    try:
        print("Iniciando o bot...")
        
        wait = WebDriverWait(driver, 10)       
        bolinha = driver.find_elements(By.CLASS_NAME, bolinha_notificacao) # Procura a bolinha de notificação
        clica_bolinha = bolinha[-1] # Clica na ultima notificação presente
        acao_bolinha = webdriver.common.action_chains.ActionChains(driver)
        acao_bolinha.move_to_element_with_offset(clica_bolinha, 0, -20) # Move o mouse para cima da notificação
        acao_bolinha.click()
        acao_bolinha.perform()                  # Executa o primeiro clique
        acao_bolinha.click()
        acao_bolinha.perform()                  # Executa o segundo clique
        time.sleep(2)

        # Obtém o número do telefone do cliente e a última mensagem 
        telefone_cliente = driver.find_element(By.XPATH, telefone_xpath)
        telefone_final = telefone_cliente.text
        print(telefone_final)
        time.sleep(3)


        todas_as_mensagens = driver.find_elements(By.CLASS_NAME, msg_cliente)
        todas_as_mensagens_texto = [e.text for e in todas_as_mensagens]
        msg = todas_as_mensagens_texto[-1]
        print(msg)
        time.sleep(4)



        # Envia a mensagem de resposta

        resposta = requests.get('http://localhost/bot_php/index.php?', params={'msg': msg, 'telefone': telefone_final, 'usuario': usuario}, headers=agent) 
        time.sleep(1)
        resposta = resposta.text
        campo_de_texto = driver.find_element(By.XPATH, caixa_msg)
        campo_de_texto.click()
        time.sleep(2)
        campo_de_texto.send_keys(resposta, Keys.ENTER)
        time.sleep(1)

        # Fecha o contato

        webdriver.ActionChains(driver).send_keys(Keys.ESCAPE).perform()




    except Exception as e:
        print("Erro no bot:", e)
        
        




    
while True:
    bot()
    print("Aguardando novas Mensagens.")
    time.sleep(5)




    