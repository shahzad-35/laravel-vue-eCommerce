export function setUser(state, user) {
    state.user.data = user;
    if (user) {
        sessionStorage.setItem("user", JSON.stringify(user)); // Store the user object as a string
    } else {
        sessionStorage.removeItem("user");
    }
}

export function setToken(state, token) {
    state.user.token = token;
    if (token) {
        sessionStorage.setItem('TOKEN', token);
    } else {
        sessionStorage.removeItem('TOKEN');
    }
}
