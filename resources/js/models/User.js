class User {
    constructor(data) {
        this._name = data.name;
        this._email = data.email;
        this._isVerified = data.email_verified_at !== null;
    }

    get email() {
        return this._email;
    }

    get name() {
        return this._name;
    }

    get isVerified() {
        return this._isVerified;
    }
}

export default User;