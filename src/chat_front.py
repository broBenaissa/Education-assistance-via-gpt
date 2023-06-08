import gradio as gr
import requests,json,array
import EXO,openai,os

username = 'admin'
password = 'admin'
dataArray = []
messages = [{"role": "system", "content": 'You are a clever english assistant.Your goal is to help solving this exercice stap-by-step without giving solution:'+EXO.exercice+'.'}]
Api_key = os.getenv("OPENAI_API_KEY")
openai.api_key=Api_key

with gr.Blocks(title="Education assistante", css=".gradio-container {background-color: #AD773F}") as demo:
    with gr.Row():
        with gr.Column():
            exoMarkdown = gr.TextArea(EXO.exercice, elem_id='exo_id',label='Exercice')
            gr.Markdown('\n\n')
            order = gr.Textbox(label='Make your order here.')
            submitOrder = gr.Button('Submit order')
            output = gr.Textbox(label='Orders')

        with gr.Column():
            chatbot = gr.Chatbot(label='History')
            msg = gr.Textbox(label='Message')
            with gr.Row():
                clear = gr.Button("Clear")
                regenerate = gr.Button("Regenerate")

    def pythonResponse(message, chat_history):
        messages.append({"role": "user", "content": message})
        response = openai.ChatCompletion.create(model="gpt-3.5-turbo", messages=messages)
        system_message = response["choices"][0]["message"]
        print(response)
        messages.append(system_message)
        #print('aaaaaaaaaaaaaaaaaaaa')
        #print(system_message)

        chat_content = ""
        for messag in messages:
            if messag['role'] != 'system':
                 chat_content += messag['role'] + ": " + messag['content'] + "\n\n"

       # chat_history.append(chat_content)

        return chat_content
    


    def respond(message, chat_history):
        
        data = {
            'user_input': message,
            'order': order,
            'exo': EXO.exercice,
            'dataArray': dataArray,
            'api_key':Api_key
        }
        
        response = requests.post('http://localhost/ChatAssistancePHP/src/chat_backend.php', data=data)
        #if (response.status_code == 200): 
       

        #output.append((message))
        bot_response = response.text.strip()  # Extract the response text
        
        contain_blocked_word = False
        for allowed_word in dataArray:
            if allowed_word in bot_response.lower():
                contain_blocked_word = True
                break
        if(contain_blocked_word):
            blocked='chatGPT give response'
            bot_response += '\n\n ->' +blocked
        chat_history.append((message,bot_response))  # Add discussion to history queue
        return "", chat_history

    def getOrder(order):
        items = order.split(",")
        dataArray.extend(items)
        list_str = '.\n'.join(dataArray)  # Convert the list to a string with line breaks
        return list_str

    msg.submit(respond, [msg, chatbot], [msg, chatbot])
    clear.click(lambda: None, None, chatbot, queue=False)
    submitOrder.click(getOrder, inputs=order, outputs=output, api_name="getOrder")
    submitOrder.click(lambda x: gr.update(value=''), [],[order])


if __name__ == "__main__":
    demo.launch(favicon_path='./src/icon/icon.png',show_error=True ,server_port=7862 )#, auth=(username, password))
