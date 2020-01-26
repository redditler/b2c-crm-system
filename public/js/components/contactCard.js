class Card {
      constructor(props){
            this.props = props;
      }

      createCard(){
            let p = this.props;
            let card = '';

            card = `<div class="card" data-id="${p.id}" ondblclick="window.open(' contact/${p.id}/edit');">
                        <div class="card__header">
                              <span class="card__create-date">${p.date}</sapn>
                        </div>
                        <div class="card__body">
                              <div class="card__contact-info">
                                    <div class="contact-info__row location">
                                          <img src="/img/icons/location.png">
                                          <span class="contact__value">${p.region}</span>
                                    </div>
                                    <div class="contact-info__row client">
                                          <img src="/img/icons/user.png">
                                          <span class="contact__value">${p.name}</span>
                                    </div>
                                    <div class="contact-info__row phone">
                                          <img src="/img/icons/phone.png">
                                          <span class="contact__value">${p.phone}</span>
                                    </div>
                              </div>
                              <div class="card__status-info">
                                    <div class="statusbar">
                                          <div class="statusbar__header">
                                                <span class="statusbar__value">${p.statusName}</span>
                                          </div>
                                          <div class="statusbar__footer status-${p.statusId}">
                                                <div class="progres-1"></div>
                                                <div class="progres-2"></div>
                                                <div class="progres-3"></div>
                                                <div class="progres-4"></div>
                                                <div class="progres-5"></div>
                                                <div class="progres-6"></div>
                                          </div>
                                    </div>
                              </div>
                              <div class="card__quality-info ${p.quality == 'Не установлен' ? 'default' : ''}">
                                    <span class="quality__value ">${p.quality}</span>
                              </div>
                        </div>
                        <div class="card__footer">
                              <div data-user="${p.id}" class="progress_user">100%</div>
                        </div>
                  </div>`

            return card;
      }
}