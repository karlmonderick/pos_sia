export default class Gate{

    constructor(user){
        this.user = user;
    }

    isAdmin(){
        return this.user.type === 'Admin';
    }
    isManager(){
        return this.user.type === 'Manager';
    }
    isSecretary(){
        return this.user.type === 'Secretary';
    }
    isDeveloper(){
        return this.user.type === 'Developer';
    }
    isManagerOrSecretary(){
        if(this.user.type === 'Manager' || this.user.type === 'Secretary'){
            return true;
        }

    }

}
