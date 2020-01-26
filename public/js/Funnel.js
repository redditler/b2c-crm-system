$(document).ready(function(){

let app = new PIXI.Application({ width: 400, height: 280, transparent: true, antialias: true});
let elem = document.getElementById('canvasBlock');
elem.appendChild(app.view);

let container = new PIXI.Container();
app.stage.addChild(container);

let funnelWidth = funnelHeight = 260;
let arrColor = [0xff0000, 0xee5d00, 0xc27700, 0xdcac00, 0xffff09, 0x72d103, 0x528e0c];
let sectionHeight = funnelHeight/arrColor.length;
let sectionFunnelHeight = 25;
let intervalHeight = sectionHeight - sectionFunnelHeight;
let angleA = Math.atan(funnelHeight/(funnelWidth / 2)) / Math.PI * 180;
let widthInterval = intervalHeight / Math.tan(Math.atan(funnelHeight/(funnelWidth / 2)));
let widthIntervalFunnel = sectionFunnelHeight/Math.tan(Math.atan(funnelHeight/(funnelWidth / 2)));
let summaryWidthInterval = widthInterval + widthIntervalFunnel;

function drawFunnel(argument) {
    
    for (var i = 0; i < arrColor.length; i++) {
        let widthFunnel = funnelWidth + 60 - 10 * i;
        let percentGradient = (20 - i * 3) / 100;
        let gradTexture = createGradTexture(PIXI.utils.hex2string(arrColor[i]), widthFunnel, percentGradient);
        let ticker = new PIXI.ticker.Ticker();
        let sectionCont = new PIXI.Container();                
        let sectionFunnel = new PIXI.Graphics();
        // sectionFunnel.beginFill(arrColor[i]);
        sectionFunnel.beginTextureFill(gradTexture);
        if (i === 0) {            
            sectionFunnel.moveTo(0, intervalHeight);
            sectionFunnel.lineTo(funnelWidth, intervalHeight);
            sectionFunnel.lineTo(funnelWidth - widthIntervalFunnel, sectionHeight);
            sectionFunnel.lineTo(widthIntervalFunnel, intervalHeight + sectionFunnelHeight);
            sectionFunnel.lineStyle(1, 0xbf0605);
            sectionFunnel.beginFill(0xbf0605);            
            sectionFunnel.drawEllipse (funnelWidth / 2, intervalHeight, funnelWidth / 2, intervalHeight - 8);
            sectionFunnel.lineStyle(0, arrColor[i]);
            // sectionFunnel.beginFill(arrColor[i]);
            sectionFunnel.beginTextureFill(gradTexture);  
            sectionFunnel.drawEllipse (funnelWidth / 2, sectionHeight, (funnelWidth - widthIntervalFunnel * 2) / 2, intervalHeight - 8);
        } else {
            sectionFunnel.moveTo(summaryWidthInterval * i, (sectionHeight * i) + intervalHeight);
            sectionFunnel.lineTo(summaryWidthInterval + (funnelWidth - (summaryWidthInterval ) * (i + 1)), (sectionHeight * i) + intervalHeight);
            sectionFunnel.lineTo(summaryWidthInterval + (funnelWidth - (summaryWidthInterval ) * (i + 1)) - widthIntervalFunnel, ((sectionHeight * i) + intervalHeight) + sectionFunnelHeight);
            sectionFunnel.lineTo((summaryWidthInterval * i) + widthIntervalFunnel, ((sectionHeight * i) + intervalHeight) + sectionFunnelHeight);
            sectionFunnel.beginFill(0x454545);      
            sectionFunnel.drawEllipse (funnelWidth / 2, (sectionHeight * i) + intervalHeight, (funnelWidth / 2) - summaryWidthInterval * i, intervalHeight - 8);
            // sectionFunnel.beginFill(arrColor[i]); 
            sectionFunnel.beginTextureFill(gradTexture);      
            sectionFunnel.drawEllipse (funnelWidth / 2, ((sectionHeight * i) + intervalHeight) + sectionFunnelHeight, ((funnelWidth / 2) - summaryWidthInterval * i) - widthIntervalFunnel, intervalHeight - 8);
        }

        sectionFunnel.interactive = true;
        sectionFunnel.buttonMode = true;                
        sectionFunnel.on('mouseover', (event) => {
            sectionCont.alpha = 0.5;
            let anim = () => {
                sectionCont.alpha += 0.05;
                if (sectionCont.alpha >= 1) {
                    ticker.stop();
                    ticker.remove(anim);
                }
            }
            ticker.add(anim);
            ticker.start();
        });
        sectionFunnel.endFill();
        sectionCont.addChild(sectionFunnel);
        container.addChild(sectionCont);
    }
}

let overCont,
    radius = 13,
    arrStatusText = ['Все', "Новый", "Обработка", "Замер", "Предложение", "Счет", "Оплачен"];
    arrStatus = [
        {
            statusId: ['5', '11', '12', '13', '14', '15'],
            name: "Все"
        },
        {
            statusId: ["5"],
            name: "Новый"
        },
        {
            statusId: ["11"],
            name: "Обработка"
        },
        {
            statusId: ["12"],
            name: "Замер"
        },
        {
            statusId: ["13"],
            name: "Предложение"
        },
        {
            statusId: ["14"],
            name: "Счет"
        },
        {
            statusId: ["15"],
            name: "Оплачен"
        }
    ];

let btnCont = undefined;
function drawCircle(text, fun) {
    let func = fun;    
    if (btnCont !== undefined) {
        for (var i = 0; i < btnCont.children.length; i++) {
            btnCont.children[i].children[1].text = text[i]
        }        
    } else {
        btnCont = new PIXI.Container();
        container.addChild(btnCont);
        for (var i = 0; i < arrColor.length; i++) {
            let ticker = new PIXI.ticker.Ticker();
            let circleCont = new PIXI.Container();
            circleCont.x = funnelWidth + 25 - 10 * i;
            circleCont.y = ((sectionHeight * i) + intervalHeight) + radius;
            btnCont.addChild(circleCont);
            let circle = new PIXI.Graphics();
            circle.lineStyle(2.5, 0x454545);
            circle.beginFill(arrColor[i]);
            circle.drawEllipse (0, 0, radius + 10, radius);
            circleCont.addChild(circle);
            let circleText = new PIXI.Text(text[i]);
            circleText.style = {
                fontSize : 13,
                fontWeight: 'bold',
            };
            circleCont.addChild(circleText);
            circleText.anchor.set(0.5);
            circle.interactive = true;
            circle.buttonMode = true;
            
            circle.on('mouseover', (event) => {
                circleCont.alpha = 0;
                let anim = () => {
                    circleCont.alpha += 0.05;
                    if (circleCont.alpha >= 1) {
                        ticker.stop();
                        ticker.remove(anim);
                    }
                }
                ticker.add(anim);
                ticker.start();
            });

            let statusId = arrStatus[i].statusId;
            circle.on('click', (event) => {
                leadStatusId = statusId;
                func();
            });

            let textStatus = new PIXI.Text(arrStatus[i].name);
            textStatus.style = {
                fontSize : 16,
                fontWeight: 'bold',
            };
            textStatus.anchor.set(0, 0.5);
            textStatus.x = radius + 15;
            circleCont.addChild(textStatus);
        }
    }    
}

function createGradTexture(color, width, percent) {
    const quality = width;
    const canvas = document.createElement('canvas');
    canvas.width = quality;
    canvas.height = 1;

    const ctx = canvas.getContext('2d');
    const grd = ctx.createLinearGradient(0, 0, quality, 0);
    grd.addColorStop(0, color);
    grd.addColorStop(0.5 - percent, color);
    grd.addColorStop(0.5, 'white');
    grd.addColorStop(0.5 + percent, color);
    grd.addColorStop(1, color);

    ctx.fillStyle = grd;
    ctx.fillRect(0, 0, quality, 1);

    return PIXI.Texture.from(canvas);
}

drawCircle();
})