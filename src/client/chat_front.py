import gradio as gr
import requests,json,array
import content,openai,os

a=0
i=0
username = 'admin'
password = 'admin'
dataArray = []
messages = [{"role": "system", "content": 'You are a clever english assistant.Your goal is to help solving this exercice stap-by-step without giving solution:'+content.exercice+'.'}]
Api_key = os.getenv("OPENAI_API_KEY")
openai.api_key=Api_key

with gr.Blocks(title="Education assistante", css=".gradio-container {background-color: #AD773F}") as demo:
    with gr.Row():
        with gr.Column():
            exoMarkdown = gr.TextArea(content.exercice, elem_id='exo_id',label='Exercice')#here is for exercice
            gr.Markdown('\n\n')#separate spacing
            order = gr.Textbox(label='Make your order here.')#teacher can add some spesifique word to block
            submitOrder = gr.Button('Submit order')#submit order manually
            output = gr.Textbox(label='Orders') #here show teacher's list of orders

        with gr.Column():
            chatbot = gr.Chatbot(label='History')#where messages apeare
            msg = gr.Textbox(label='Message')#here you can write your message
    
    def pythonResponse(message, chat_history):
        messages.append({"role": "user", "content": message})
        response = openai.ChatCompletion.create(model="gpt-3.5-turbo", messages=messages)
        system_message = response["choices"][0]["message"]
        print(response)
        messages.append(system_message)
        
        chat_content = ""
        for messag in messages:
            if messag['role'] != 'system':
                 chat_content += messag['role'] + ": " + messag['content'] + "\n\n"

       # chat_history.append(chat_content)

        return chat_content
    
    def respond(message, chat_history):
        global i
        data = {
            'user_input': message,
            'order': order,
            'exo': content.exercice,
            'dataArray': dataArray,
            'api_key':Api_key
        }
        
        response = requests.post('http://localhost/ChatAssistancePHP/src/chat_backend.php', data=data)
        #if (response.status_code == 200): 
       

        #output.append((message))
        bot_response = response.text.strip()  # Extract the response text
        contain_blocked_word = False
        for blocked_word in dataArray:
            if blocked_word in bot_response.lower():
                contain_blocked_word = True
                break
        if(contain_blocked_word):
            i += 1
            blocked='chat bot gives student response!!\nFor:'+str(i)
            bot_response += '\n\n ->' +blocked

        chat_history.append((message,bot_response))  # Add discussion to history queue
        return "", chat_history

    def getOrder(order):
        items = order.split(",")
        dataArray.extend(items)
        list_str = '\n'.join(dataArray)  # Convert the list to a string with line breaks
        return list_str

    msg.submit(respond, [msg, chatbot], [msg, chatbot])
    #clear.click(lambda: None, None, chatbot, queue=False)
    submitOrder.click(getOrder, inputs=order, outputs=output, api_name="getOrder")
    submitOrder.click(lambda x: gr.update(value=''), [],[order])


if __name__ == "__main__":
    demo.launch(favicon_path='./icon/icon.png',show_error=True ,server_port=7862 )
