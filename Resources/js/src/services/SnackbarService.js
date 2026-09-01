import snackbarStore from 'sulu-admin-bundle/stores/snackbarStore';

const DEFAULT_DURATION = 3500;

const SnackbarService = {
    show({text, type = 'success', duration = DEFAULT_DURATION}) {
        snackbarStore.add({text, type}, duration);
    },
};

export default SnackbarService;
