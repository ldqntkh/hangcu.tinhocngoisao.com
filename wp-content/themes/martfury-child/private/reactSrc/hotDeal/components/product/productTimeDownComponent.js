import React, {Component} from 'react';

export default class ProductTimeDownComponent extends Component {

    // component nhan tham so dau vao la blocktime
    constructor(props) {
        super(props);
        this.state = {
            displayTime: true
        }
    }

    componentWillMount() {
        if (this.props.sale_end_time !== null)
            this.setTimeDown();
    }

    componentDidMount() {
        if (this.props.sale_end_time !== null)
            this.interval  = setInterval(this.timerTick, 1000);
    }

    componentWillUnmount() {
        clearInterval(this.interval);
    }

    setTimeDown = () => {
        // sale_end_time is value with type is date
        var end_time_day = new Date(this.props.sale_end_time).getDay();

        var currentTime = new Date();
        var currentDay = currentTime.getDay();
        var currentHour = currentTime.getHours();
        
        var day = end_time_day - currentDay;
        var hours = 24 - currentHour;
        if (day > 0) hours += 24 * day;
    
        if (hours < 0) {
            this.setState({
                displayTime: false
            })
        }

        let minutes = 59 - currentTime.getMinutes();
        let seconds = 59 - currentTime.getSeconds();

        this.setState({
            timeNow : {
                "hours" : hours,
                "minutes" : minutes,
                "seconds" : seconds
            }
        });
    }

    timerTick = ()=> {
        var timeNow = this.state.timeNow;
        if (timeNow !== null) {
            var seconds = timeNow.seconds - 1,
            minutes = timeNow.minutes,
            hours = timeNow.hours;
            if (hours < 0) {
                clearInterval(this.interval);
                return;
            }
            if (seconds < 0) {
                seconds = 59;
                minutes = minutes - 1;
                if (minutes < 0) {
                    minutes = 59;
                    hours = hours - 1;
                    if (hours < 0) {
                        clearInterval(this.interval);
                        hours = 0;
                    }
                }
            }
            let data = {
                "hours" : hours,
                "minutes" : minutes,
                "seconds" : seconds
            };
            this.setState({
                timeNow : data
            });
        }
    }

    renderEmptyTime =()=> {
        return <div className="block-time-down">
                    <span >  </span>
                    <span >  </span>
                    <span >  </span>
                </div>
    }

    render() {
        var timeNow = this.state.timeNow;
        if (timeNow == null) return this.renderEmptyTime();
        return(
            this.state.displayTime ?
                <div className="block-time-down">
                    <i className="fa fa-clock-o"></i>
                    <span className="hours">{ timeNow.hours >= 10 ? '' : '0' }{timeNow.hours}</span>
                    <span>:</span>
                    <span className="minutes">{ timeNow.minutes >= 10 ? '' : '0' }{timeNow.minutes}</span>
                    <span>:</span>
                    <span className="seconds">{ timeNow.seconds >= 10 ? '' : '0' }{timeNow.seconds}</span>
                </div>
                : this.renderEmptyTime()
        );
    }
}