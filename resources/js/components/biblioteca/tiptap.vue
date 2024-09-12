<template>
  <div v-if="editor">
      <button @click="editor.chain().focus().insertTable({ rows: 3, cols: 3, withHeaderRow: true }).run()">
      insertTable
    </button>
    <button @click="editor.chain().focus().addColumnBefore().run()" :disabled="!editor.can().addColumnBefore()">
      addColumnBefore
    </button>
    <button @click="editor.chain().focus().addColumnAfter().run()" :disabled="!editor.can().addColumnAfter()">
      addColumnAfter
    </button>
    <button @click="editor.chain().focus().deleteColumn().run()" :disabled="!editor.can().deleteColumn()">
      deleteColumn
    </button>
    <button @click="editor.chain().focus().addRowBefore().run()" :disabled="!editor.can().addRowBefore()">
      addRowBefore
    </button>
    <button @click="editor.chain().focus().addRowAfter().run()" :disabled="!editor.can().addRowAfter()">
      addRowAfter
    </button>
    <button @click="editor.chain().focus().deleteRow().run()" :disabled="!editor.can().deleteRow()">
      deleteRow
    </button>
    <button @click="editor.chain().focus().deleteTable().run()" :disabled="!editor.can().deleteTable()">
      deleteTable
    </button>
    <button @click="editor.chain().focus().mergeCells().run()" :disabled="!editor.can().mergeCells()">
      mergeCells
    </button>
    <button @click="editor.chain().focus().splitCell().run()" :disabled="!editor.can().splitCell()">
      splitCell
    </button>
    <button @click="editor.chain().focus().toggleHeaderColumn().run()" :disabled="!editor.can().toggleHeaderColumn()">
      toggleHeaderColumn
    </button>
    <button @click="editor.chain().focus().toggleHeaderRow().run()" :disabled="!editor.can().toggleHeaderRow()">
      toggleHeaderRow
    </button>
    <button @click="editor.chain().focus().toggleHeaderCell().run()" :disabled="!editor.can().toggleHeaderCell()">
      toggleHeaderCell
    </button>
    <button @click="editor.chain().focus().mergeOrSplit().run()" :disabled="!editor.can().mergeOrSplit()">
      mergeOrSplit
    </button>
    <button @click="editor.chain().focus().setCellAttribute('backgroundColor', '#FAF594').run()" :disabled="!editor.can().setCellAttribute('backgroundColor', '#FAF594')">
      setCellAttribute
    </button>
    <button @click="editor.chain().focus().fixTables().run()" :disabled="!editor.can().fixTables()">
      fixTables
    </button>
    <button @click="editor.chain().focus().goToNextCell().run()" :disabled="!editor.can().goToNextCell()">
      goToNextCell
    </button>
    <button @click="editor.chain().focus().goToPreviousCell().run()" :disabled="!editor.can().goToPreviousCell()">
      goToPreviousCell
    </button>



    <v-toolbar dense v-if="editor">
      <v-btn
        icon
        @click="editor.chain().focus().toggleHeading({ level: 2 }).run()"
        :class="{ 'is-active': editor.isActive('heading', { level: 2 }) }"
      >
        <v-icon>mdi-format-header-2</v-icon>
      </v-btn>
      <v-btn
        icon
        @click="editor.chain().focus().toggleHeading({ level: 3 }).run()"
        :class="{ 'is-active': editor.isActive('heading', { level: 3 }) }"
      >
        <v-icon>mdi-format-header-3</v-icon>
      </v-btn>
      <v-btn
        icon
        @click="editor.chain().focus().toggleHeading({ level: 4 }).run()"
        :class="{ 'is-active': editor.isActive('heading', { level: 4 }) }"
      >
        <v-icon>mdi-format-header-4</v-icon>
      </v-btn>

      <v-btn
        @click="editor.chain().focus().setParagraph().run()"
        :class="{ 'is-active': editor.isActive('paragraph') }"
        icon
      >
        <v-icon>mdi-format-paragraph</v-icon>
      </v-btn>

      <v-btn
        @click="editor.chain().focus().toggleBold().run()"
        :class="{ 'is-active': editor.isActive('bold') }"
        icon
      >
        <v-icon>mdi-format-bold</v-icon>
      </v-btn>

      <v-btn
        @click="editor.chain().focus().toggleItalic().run()"
        :class="{ 'is-active': editor.isActive('italic') }"
        icon
      >
        <v-icon>mdi-format-italic</v-icon>
      </v-btn>

      <v-btn
        @click="editor.chain().focus().toggleStrike().run()"
        :class="{ 'is-active': editor.isActive('strike') }"
        icon
      >
        <v-icon>mdi-format-strikethrough</v-icon>
      </v-btn>

      <v-btn
        @click="editor.chain().focus().toggleHighlight().run()"
        :class="{ 'is-active': editor.isActive('highlight') }"
        icon
      >
        <v-icon>mdi-marker</v-icon>
      </v-btn>

      <v-btn
        @click="editor.chain().focus().toggleBulletList().run()"
        :class="{ 'is-active': editor.isActive('bulletList') }"
        icon
      >
        <v-icon>mdi-format-list-bulleted</v-icon>
      </v-btn>
      <v-btn
        @click="editor.chain().focus().toggleOrderedList().run()"
        :class="{ 'is-active': editor.isActive('orderedList') }"
        icon
      >
        <v-icon>mdi-format-list-numbered</v-icon>
      </v-btn>

      <v-btn
        @click="editor.chain().focus().setTextAlign('left').run()"
        :class="{ 'is-active': editor.isActive({ textAlign: 'left' }) }"
        icon
      >
        <v-icon>mdi-format-align-left</v-icon>
      </v-btn>

      <v-btn
        @click="editor.chain().focus().setTextAlign('center').run()"
        :class="{ 'is-active': editor.isActive({ textAlign: 'center' }) }"
        icon
      >
        <v-icon>mdi-format-align-center</v-icon>
      </v-btn>

      <v-btn
        @click="editor.chain().focus().setTextAlign('right').run()"
        :class="{ 'is-active': editor.isActive({ textAlign: 'right' }) }"
        icon
      >
        <v-icon>mdi-format-align-right</v-icon>
      </v-btn>

      <v-btn
        @click="editor.chain().focus().setTextAlign('justify').run()"
        :class="{ 'is-active': editor.isActive({ textAlign: 'justify' }) }"
        icon
      >
        <v-icon>mdi-format-align-justify</v-icon>
      </v-btn>
    </v-toolbar>

    <editor-content :editor="editor" />
  </div>
</template>

<script>
import { Editor, EditorContent } from "@tiptap/vue-2";
import StarterKit from "@tiptap/starter-kit";
import TextAlign from "@tiptap/extension-text-align";
import Highlight from "@tiptap/extension-highlight";
import Table from '@tiptap/extension-table'
import TableRow from '@tiptap/extension-table-row'
import TableCell from '@tiptap/extension-table-cell'
import TableHeader from '@tiptap/extension-table-header'


const CustomTableCell = TableCell.extend({
  addAttributes() {
    return {
      // extend the existing attributes …
      ...this.parent?.(),

      // and add a new one …
      backgroundColor: {
        default: null,
        parseHTML: element => {
          return {
            backgroundColor: element.getAttribute('data-background-color'),
          }
        },
        renderHTML: attributes => {
          return {
            'data-background-color': attributes.backgroundColor,
            style: `background-color: ${attributes.backgroundColor}`,
          }
        },
      },
    }
  },
})

export default {
  components: {
    EditorContent,
  },

  data() {
    return {
      editor: null,

      dropdown_edit: [
        { text: "100%" },
        { text: "75%" },
        { text: "50%" },
        { text: "25%" },
        { text: "0%" },
      ],
    };
  },

  props: {
    value: {
      type: String,
      default: "",
    },
  },

  watch: {
    value(value) {
      // HTML
      const isSame = this.editor.getHTML() === value;

      // JSON
      // const isSame = this.editor.getJSON().toString() === value.toString()

      if (isSame) {
        return;
      }

      this.editor.commands.setContent(this.value, false);
    },
  },

  mounted() {
    this.editor = new Editor({
      extensions: [
        StarterKit,
        TextAlign.configure({
          types: ["heading", "paragraph"],
        }),
        Highlight,
        Table.configure({
          resizable: true,
        }),
        TableRow,
        TableHeader,
        // Default TableCell
        // TableCell,
        // Custom TableCell with backgroundColor attribute
        CustomTableCell,
      ],
      content: this.value,
      onUpdate: () => {
        // HTML
        this.$emit("input", this.editor.getHTML());

        // JSON
        // this.$emit('update:modelValue', this.editor.getJSON())
      },
    });
  },

  beforeDestroy() {
    this.editor.destroy();
  },
};
</script>


<style lang="scss">
/* Basic editor styles */
.ProseMirror {
  margin-top: 2rem;

  > * + * {
    margin-top: 0.75em;
  }

  ul,
  ol {
    padding: 0 1rem;
  }

  h1,
  h2,
  h3,
  h4,
  h5,
  h6 {
    line-height: 1.1;
  }

  code {
    background-color: rgba(#616161, 0.1);
    color: #616161;
  }

  pre {
    background: #0d0d0d;
    color: #fff;
    font-family: "JetBrainsMono", monospace;
    padding: 0.75rem 1rem;
    border-radius: 0.5rem;

    code {
      color: inherit;
      padding: 0;
      background: none;
      font-size: 0.8rem;
    }
  }

  mark {
    background-color: #faf594;
  }

  img {
    max-width: 100%;
    height: auto;
  }

  blockquote {
    padding-left: 1rem;
    border-left: 2px solid rgba(#0d0d0d, 0.1);
  }

  hr {
    border: none;
    border-top: 2px solid rgba(#0d0d0d, 0.1);
    margin: 2rem 0;
  }
}

/* Table-specific styling */
.ProseMirror {
  table {
    border-collapse: collapse;
    table-layout: fixed;
    width: 100%;
    margin: 0;
    overflow: hidden;

    td,
    th {
      min-width: 1em;
      border: 2px solid #ced4da;
      padding: 3px 5px;
      vertical-align: top;
      box-sizing: border-box;
      position: relative;

      > * {
        margin-bottom: 0;
      }
    }

    th {
      font-weight: bold;
      text-align: left;
      background-color: #f1f3f5;
    }

    .selectedCell:after {
      z-index: 2;
      position: absolute;
      content: "";
      left: 0; right: 0; top: 0; bottom: 0;
      background: rgba(200, 200, 255, 0.4);
      pointer-events: none;
    }

    .column-resize-handle {
      position: absolute;
      right: -2px;
      top: 0;
      bottom: -2px;
      width: 4px;
      background-color: #adf;
      pointer-events: none;
    }
  }
}

.tableWrapper {
  overflow-x: auto;
}

.resize-cursor {
  cursor: ew-resize;
  cursor: col-resize;
}


</style>