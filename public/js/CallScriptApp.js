// class CallScriptApp extends React.Component{
//       constructor(props){
//             super(props);

//             this.state = {
//                   themes: [],
//                   quickAnswers: 'Для выбранной темы нет быстрых переходов',
//                   dialog: [],
//                   dialogId: 'Отсутствует',
//                   dialogThemeId: '',
//             }

//             // Смена темы/Старт диалога
//             this.hendleChangeThemes = this.hendleChangeThemes.bind(this);

//             // Отчистить диалог
//             this.hendleClearDialog = this.hendleClearDialog.bind(this);

//             // Закнчить диалог
//             this.hendleEndDialog = this.hendleEndDialog.bind(this);

//             // Выбрать ответ
//             this.handlePickAnswer = this.handlePickAnswer.bind(this);
//       }

//       // Смена темы/Старт диалога
//       hendleChangeThemes(e){
//             this.setState({
//                   dialog: [],
//                   dialogId: new Date().getTime(),
//                   dialogThemeId: e.target.value,
//             },()=> {
//                   axios.post('/callscripts/getQuickQuestion', {topic: this.state.dialogThemeId})
//                         .then(response => this.setState({ quickAnswers: response.data }))
                  
//                   axios.post('/callscripts/questionnaire', {topic: this.state.dialogThemeId, parent_id: -1, call_id: this.state.dialogId})
//                         .then(response => response.data)
//                         .then(response => {
//                               if(!response.has_response) return;
//                               this.setState(state => {
//                                     return { dialog: [{ id: response.id, answers: response.variants, instructions: response.instructions, question: response.question_text, type: response.has_response}]  }; 
//                               });
//                         })
//             })
//       }

//       // Выбрать ответ
//       handlePickAnswer(e){
//             const findDialogIndex = (obj, val) =>{
//                   return obj.id == val;
//             }

//             axios.post('/callscripts/questionnaire', {topic: this.state.dialogThemeId, parent_id: e.props.id , answered: e.props.answered, next_id: e.props['data-link'], call_id: this.state.dialogId})
//                   .then(response => response.data)
//                   .then(response => {
//                         if(!response.has_response) return;
//                         this.setState(state => {
//                               let index = state.dialog.findIndex(item => item.id == response.id && item.question == response.question_text);
//                               console.log(index)
//                               if(index >= 0) return { dialog: state.dialog.splice(0, index, { id: response.id, answers: response.variants, instructions: response.instructions, question: response.question_text, type: response.has_response})  }; 
//                               if(index < 0) return { dialog: state.dialog.concat([{ id: response.id, answers: response.variants, instructions: response.instructions, question: response.question_text, type: response.has_response}]) }; 
//                         });
//                   });
//       }

//       // Отчистить диалог
//       hendleClearDialog(e){
//             this.setState({
//                   dialog: [],
//             })
//       }

//       // Закнчить диалог
//       hendleEndDialog(e){
//             console.log(e);
//       }

//       componentDidMount(){
//             axios.get('/callscripts/getTopicCall')
//                   .then( ( response ) => { this.setState({ themes: response.data.topics })});
//       }

//       render(){
//             return(
//                   <section  className="content__wrapper" data-id="callscript">
//                         <DialogControls 
//                               themes = {this.state.themes} 
//                               dialogId = {this.state.dialogId}
//                               endEvent = { this.hendleEndDialog }
//                               clearEvent = { this.hendleClearDialog }
//                               changeEvent = {this.hendleChangeThemes}/>
                             
//                         <Dialog event={this.handlePickAnswer} dialog = {this.state.dialog}/>
//                   </section>
//             )
//       }
// }

// class Dialog extends React.Component{
//       constructor(props){
//             super(props);
//       }

//       render(){
//             return(
//                   <DialogElement event={this.props.event} dialog = {this.props.dialog} />
//             )
//       }
// }

// class DialogElement extends React.Component{
//       constructor(props){
//             super(props);
//       }

//       render(){
//             return(
//                   <div>
//                         {this.props.dialog.map(item=>(
//                               <div  key = {item.id}>
//                                     <Question title = {item.question }/>
//                                     <AnswersList data={item.answers} id={item.id} type={item.type}  pickAnsver={this.props.event}/>
//                               </div>
//                         ))}
//                   </div>
//             )
//       }
// }

// class AnswersList extends React.Component{
//       constructor(props){
//             super(props);
//       }

//       render(){
//             if(this.props.type){
//                   return(
//                         <div>
//                               {this.props.data.map(item => (
//                                     <Answer event={this.props.pickAnsver} key={item.id} id={this.props.id} answered={item.id} data-type={item.type} data-link={item.link} title={item.title} />
//                               ))}
//                         </div>
//                   )
//             }else{
//                   return(
//                         <h1>end</h1>
//                   )
//             }
//       }
// }

// class Answer extends React.Component{
//       constructor(props){
//             super(props);
//       }
     
//       render(){
//             return(
//                   <div onClick={() => this.props.event(this)} data-type={this.props.type} data-link={this.props.link}>{this.props.title}</div>
//             );
//       }
// }

// function Question(props) {
//       return(<h1>This id {props.title}</h1>);
// }

// class DialogControls extends React.Component{
//       constructor(props){
//             super(props);
//       }

//       render(){
//             return(
//                   <div className="container controls">
//                         <div className="container__title">
//                               <h1 className="title">Сценарии диалогов</h1>
//                         </div>
//                         <div className="container__content">
//                               <div className="content__controls">
//                                     <div className="controls__dialog">
//                                           <button type="button" onClick = {() => this.props.clearEvent(this)}>Очистить диалог</button>
//                                           <button type="button" onClick = {() => this.props.endEvent(this)}>Закнчить диалог</button>
//                                     </div>
//                                     <div className="controls__themes">
//                                           <select onChange = {this.props.changeEvent}>
//                                                 <option>Выбрать тему диалога</option>
//                                                 {this.props.themes.map(item => (
//                                                 <option key={item.id} data-public={item.is_publicated} data-description={item.topic_description} value={item.id}>
//                                                       {item.topic_name}
//                                                 </option>
//                                                 ))}
//                                           </select>
//                                     </div>
//                                     <div className="controls__id">
//                                           <span>{this.props.dialogId}</span>
//                                     </div>
//                               </div>
//                         </div>
//                   </div>
//             )
//       }
// }



class CallScriptApp extends React.Component{
      constructor(props){
            super(props);

            this.state = {
                  themes: [],
                  quickAnswers: [],
                  dialogHistory: [],
                  notes: [],

                  dialogId: 'Отсутствует',
                  dialogThemeId: '',
            }

            // Смена темы/Старт диалога
            this.hendleChangeThemes = this.hendleChangeThemes.bind(this);

            // Отчистить диалог
            this.hendleClearDialog = this.hendleClearDialog.bind(this);

            // Выбрать ответ
            this.handlePickAnswer = this.handlePickAnswer.bind(this);

            // Закнчить диалог
            this.hendleEndDialog = this.hendleEndDialog.bind(this);
      }

      // Смена темы/Старт диалога
      hendleChangeThemes(e){
            this.setState({
                  dialogHistory: [],
                  dialogId: new Date().getTime(),
                  dialogThemeId: e.target.value,
            },() =>  this.getStartDialog());
      };

      // Получение данных для старта диалога
      getStartDialog(){
            axios.post('/callscripts/getQuickQuestion', {topic: this.state.dialogThemeId})
                  .then(response => this.setState({ quickAnswers: response.data }))

            axios.post('/callscripts/questionnaire', {topic: this.state.dialogThemeId, parent_id: -1, call_id: this.state.dialogId})
                  .then(response => response.data)
                  .then(response => {
                        if(!response.has_response) return;
                        this.setState(state => {
                              return { dialogHistory: [{ 
                                          id: response.id,
                                          answers: response.variants,
                                          instructions: response.instructions,
                                          question: response.question_text,
                                          hasResponse: response.has_response}] 
                              }; 
                        }, () => console.log(this.state));
                  })
      }

      // Отчистить диалог
      hendleClearDialog(e){
            console.log(e);
      };

      // Выбрать ответ
      handlePickAnswer(e){
            e.setState({
                  cheked: true,
            });

            // let data = {
            //       topic: this.state.dialogThemeId,
		// 	parent_id: 	,
		// 	answered: , 
		// 	next_id: e.state.link,
		// 	call_id: this.state.dialogId, 
            // }
      };
      
      // Закнчить диалог
      hendleEndDialog(){};

      // Получить доступные темы раговора
      componentDidMount(){
            axios.get('/callscripts/getTopicCall')
                  .then( ( response ) => { this.setState({ themes: response.data.topics })});
      }
      
      render(){
            return(
                  <section  className="content__wrapper dialog" data-id="callscript">
                        <DialogControls
                              changeThemes = {this.hendleChangeThemes}
                              endDialog = {this.hendleEndDialog}
                              clearDialog = {this.hendleClearDialog}
                              themes={ this.state.themes }
                              dialogId={ this.state.dialogId }
                              dialogThemeId={ this.state.dialogThemeId } />
                              
                        <DialogSubControls
                              notes = {this.state.notes}
                              themes={ this.state.themes }
                              dialogId={ this.state.dialogId }
                              quickAnswers = {this.state.quickAnswers} />

                        <DialogHisotry
                              pickAnswer = {this.handlePickAnswer}
                              dialogId={ this.state.dialogId }
                              history = {this.state.dialogHistory} />
                  </section>
            )
      }
}

class DialogControls extends React.Component{
      constructor(props){
            super(props);
      }

      render(){
            return(
                  <div className="container controls">
                        <div className="container__title">
                              <h1 className="title">Сценарии диалогов</h1>
                        </div>
                        <div className="container__content">
                              <div className="content__controls">
                                    <div className="controls__dialog">
                                          <button type="button" onClick = {() => this.props.clearDialog(this)}>Очистить диалог</button>
                                          <button type="button" onClick = {() => this.props.endDialog(this)}>Закнчить диалог</button>
                                    </div>
                                    <div className="controls__themes">
                                          <select onChange = {this.props.changeThemes}>
                                                <option>Выбрать тему диалога</option>
                                                {this.props.themes.map(item => (
                                                <option key={item.id} data-public={item.is_publicated} data-description={item.topic_description} value={item.id}>
                                                      {item.topic_name}
                                                </option>
                                                ))}
                                          </select>
                                    </div>
                                    <div className="controls__id">
                                          <span>{this.props.dialogId}</span>
                                    </div>
                              </div>
                        </div>
                  </div>
            )
      }
}


class DialogHisotry extends React.Component{
      constructor(props){
            super(props);
      }

      render(){
            return(
                  <div className="container history">
                        {this.props.history.map(item => <DialogHistoryElement pickAnswer = {this.props.pickAnswer} key={new Date().getTime()} dialogId = {this.props.dialogId} data = {item}/>)}
                  </div>
            )
      }
}

class DialogHistoryElement extends React.Component{
      constructor(props){
            super(props);
      }

      render(){
            return(
                  <div className="history__element">
                        <DialogQuestion title = {this.props.data.question} id = {this.props.data.id}/>
                        <DialogAnswersList pickAnswer = {this.props.pickAnswer} id = {this.props.data.id} list = {this.props.data.answers} />
                  </div>
            )
      }
}

class DialogQuestion extends React.Component{
      constructor(props){
            super(props);
      }

      render(){
            return(
                  <div className="history__question" id = {this.props.id}>{this.props.title}</div>
            )
      }
}

class DialogAnswersList extends React.Component{
      constructor(props){
            super(props);
      }

      render(){
            return(
                  <div className="history__answers-list">
                       {this.props.list.map(item => <Answer event = {this.props.pickAnswer} id = {this.props.data.id} key = {item.id} data={item}/>)}
                  </div>
            )
      }
}

class Answer extends React.Component{
      constructor(props){
            super(props);

            this.state = {
                  parendId: this.props.id,
                  cheked: false,
                  link: this.props.data.link,
                  type: this.props.data.type,
                  id: this.props.data.id,
                  title: this.props.data.title
            }
      }

      render(){
            return(
                  <div onClick = {() => this.props.event(this)} className="answer-list__item">
                        {this.state.title}
                  </div>
            )
      }
}


class DialogSubControls extends React.Component{
      constructor(props){
            super(props);
      }

      render(){
            return(
                  <div className="container subcontrols"></div>
            )
      }
}
