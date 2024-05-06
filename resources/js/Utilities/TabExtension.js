import { Extension } from '@tiptap/core';

export const TabExtension = Extension.create({
    name: 'tab',

    addKeyboardShortcuts() {
        // Define keyboard shortcuts for tab handling
        return {
            // Handle the Tab key
            Tab: (props) => {  // Make sure to use `props` to access the editor and event
                const { editor, event } = props;  // Destructure to access the editor and event
                if (event) {
                    event.preventDefault();  // Prevent the default action
                    editor.commands.insertContent('&nbsp;&nbsp;&nbsp;&nbsp;');  // Inserts four non-breaking spaces
                    return true;  // Stop further handling
                }
                return false;  // If no event, do not handle
            },
            // Optionally handle Shift-Tab here if needed
            'Shift-Tab': (props) => {
                const { editor, event } = props;
                if (event) {
                    event.preventDefault();  // Prevent the default action
                    // Add any custom logic for Shift-Tab if necessary
                    return true;  // Stop further handling
                }
                return false;  // If no event, do not handle
            }
        };
    }
});
