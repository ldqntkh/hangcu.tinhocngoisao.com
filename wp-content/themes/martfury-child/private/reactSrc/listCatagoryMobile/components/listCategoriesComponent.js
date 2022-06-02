import React, {Component} from 'react';

const CATEGORIES_DATA_HISTORY='CATEGORIES_DATA_HISTORY';

class ListCategoriesComponent extends Component {
    constructor(props) {
        super(props);
        this.state = {
            parent_id : "0",
            category_click : null,
            prev_parent_id: [],
            prev_category_click : [],
        }
    }

    componentWillMount() {
        if (sessionStorage.getItem(CATEGORIES_DATA_HISTORY) && JSON.parse(sessionStorage.getItem(CATEGORIES_DATA_HISTORY))) {
            this.setState(JSON.parse(sessionStorage.getItem(CATEGORIES_DATA_HISTORY)))
        }
    }

    _handleCategoryClick = (item)=> {
        let {
            prev_parent_id,
            prev_category_click,
            parent_id,
            category_click
        } = this.state;
        let {
            categories
        } = this.props;
        let flag = false;
        for (let index in categories) {
            if (categories[index].menu_item_parent === item.ID.toString()) {
                flag = true;
                break;
            }
        }
        if (!flag) {
            sessionStorage.setItem(CATEGORIES_DATA_HISTORY, JSON.stringify(this.state));
            location.href = item.url;
        } else {
            prev_parent_id.push(parent_id);
            prev_category_click.push(category_click)
            this.setState({
                prev_parent_id : prev_parent_id,
                prev_category_click : prev_category_click,
                parent_id : item.ID.toString(),
                category_click : item,
            });
            sessionStorage.setItem(CATEGORIES_DATA_HISTORY, JSON.stringify(this.state));
        }
    }

    _handleCategoryClickPrev = ()=> {
        let {
            prev_parent_id,
            prev_category_click
        } = this.state;
        if (prev_category_click.length > 0) {
            let parent_id = prev_parent_id[prev_parent_id.length - 1];
            let category_click = prev_category_click[prev_category_click.length -1];
            prev_category_click.pop();
            prev_parent_id.pop();
            this.setState({
                parent_id : parent_id,
                category_click : category_click,
                prev_category_click : prev_category_click,
                prev_parent_id : prev_parent_id,
            });
        } else {
            this.setState({
                parent_id : "0",
                category_click : null,
            });
        }
        sessionStorage.setItem(CATEGORIES_DATA_HISTORY, JSON.stringify(this.state));
    }

    render() {
        let {
            categories
        } = this.props;
        let {
            category_click
        } = this.state;
        
        
        let result= [];
        for (let index in categories) {
            if (categories[index].menu_item_parent === this.state.parent_id) {
                let item = categories[index];
                result.push(
                    <div className="category-item" key={index+2}>
                        <a href="#" onClick={()=>this._handleCategoryClick(item)}>
                            <img src={item.thumbnail_image} alt=""/>
                            <span>{item.title}</span>
                        </a>
                    </div>
                );
            }
        }
        
        if (category_click !== null) {
            result.unshift(
                <div className="category-item main-item viewall" key={1}>
                    <a href={category_click.url}>
                        <span>Xem tất cả</span>
                    </a>
                </div>
            );
            result.unshift(
                <div className="category-item main-item back" key={0}>
                    <a href="#" onClick={()=> this._handleCategoryClickPrev()}>
                        <span>{category_click.title}</span>
                    </a>
                </div>
            );
        }

        // if (start_item) result.unshift(start_item);
        return(
            <React.Fragment>
                {result}
            </React.Fragment>
        );
    }
}

export default ListCategoriesComponent;