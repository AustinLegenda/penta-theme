import PentaNav from './penta-nav.js';

wp.blocks.registerBlockType("pentablocktheme/penta-header-block", {
    title: "Penta Header Block",
    edit: EditComponent,
    save: SaveComponent,
});

function EditComponent() {
    return (
        <div className="main-grid">
            <PentaNav />
        </div>
    );
}

function SaveComponent() {


    return (

        null

    );
}
