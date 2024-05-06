import { Extension } from '@tiptap/core';

export const TabExtension = Extension.create({
    name: 'tab',

    addKeyboardShortcuts() {
        return {
            // Handle the Tab key
            Tab: ({ editor, event }) => {
                event.preventDefault();  // Prevent the default action
                editor.commands.insertContent('&nbsp;&nbsp;&nbsp;&nbsp;');  // Inserts four non-breaking spaces
                return true;
            },
            // Optionally handle Shift+Tab here if needed
            'Shift-Tab': ({ editor, event }) => {
                event.preventDefault();  // Prevent the default action
                // Custom logic for Shift+Tab if necessary
                return true;
            }
        };
    }
});
