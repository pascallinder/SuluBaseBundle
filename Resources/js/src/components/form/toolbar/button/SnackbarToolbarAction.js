import {get, reaction} from 'mobx';
import {AbstractFormToolbarAction} from 'sulu-admin-bundle/views';
import SnackbarService from '../../../../services/SnackbarService';

export default class SnackbarToolbarAction extends AbstractFormToolbarAction {
    constructor(...args) {
        super(...args);

        this.snackbarDisposer = reaction(
            () => get(this.resourceFormStore.data, 'snackbar'),
            (snackbar) => {
                if (!snackbar) {
                    return;
                }

                SnackbarService.show(snackbar);
                this.resourceFormStore.change('snackbar', null, {isServerValue: true});
            },
            {fireImmediately: true},
        );
    }

    getToolbarItemConfig() {
        return null;
    }

    destroy() {
        this.snackbarDisposer();
    }
}
