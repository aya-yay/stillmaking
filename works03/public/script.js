const tasksDOM = document.querySelector(".tasks");
const formDOM = document.querySelector(".task-form");
const taskInputDOM = document.querySelector(".task-input");
const formAlertDOM = document.querySelector(".form-alert");


// /api/v1/tasksからタスクを読み込む

const showTasks = async () => {
    try {
        //自作のapiを叩く
        const { data: tasks } = await axios.get("/api/v1/tasks"); //dataという属性をtasksという形で取得
        //タスクが１つもないとき
        console.log(tasks.length);
        if(tasks.length < 1) {
            tasksDOM.innerHTML = `<h5 class="empty-list">まだタスクはありません</h5>`;
            return;
        }
        //タスクを出力
        const allTasks = tasks.map((task) => {
            const { completed, _id, name } = task; //1つ1つ取り出す新しい取り出し方

            return ` 
            <div class="single-task ${completed && "task-completed"}">
                <h5>
                    <span><i class="far fa-check-circle"></i></span>
                    ${name} 
                </h5>
                <div class="task-links">
                    <!-- 編集リンク -->
                    <a href="edit.html?id=${_id}" class="edit-link">
                        <i class="fas fa-edit"></i>
                    </a>
                    <!-- ゴミ箱リンク -->
                    <button type="button" class="delete-btn" data-id="${_id}">
                        <i class="fas fa-trash "></i>
                    </button>
                </div>
            </div>
            `;
        }).join("");
            //${completed && "task-completed"}→チェックがついていたらtask-completedクラスを付与する（丸いチェックマークがvisibleになる）        
            //※{name}は上で1つ1つ取り出したうちのname
            //配列のカンマを取り除くことができるjoin関数
        tasksDOM.innerHTML = allTasks;


    } catch (err) {
        console.log(err);
    }
};

showTasks();

//タスクを新規作成する

formDOM.addEventListener("submit", async (event) => {
    event.preventDefault();
    const name = taskInputDOM.value;

    try {
        await axios.post("/api/v1/tasks", { name: name });
        //左のname:はTask.jsのname:、右のnameは56行目の定数name(inputフォームに入力されたnameの値つまり入力されたタスク)
        showTasks();//投稿されたデータを表示する
        taskInputDOM.value = "";//submit押下後、フォームを空にする
        formAlertDOM.style.display = "block";//73行目でnoneになっているので追加しましたが出現するようにする
        formAlertDOM.textContent = "タスクを追加しました";
        formAlertDOM.classList.add("text-success");//新しいクラスをつけてcssで装飾
    } catch (err) {
        console.log(err);
        formAlertDOM.style.display = "block";//73行目でnoneになったのでエラー文を表示させる
        formAlertDOM.innerHTML = "タスクは200文字以内で入力してください";//200文字を超えた場合
        setTimeout(() => {
            formAlertDOM.style.display = "none";//（3秒後に）エラー文が消える
            formAlertDOM.classList.remove("text-success");//67行目のtext-successの色が反映されているので削除
        }, 3000);//3秒後にエラー文が消える
        ;
    }
});


//タスクを削除する

tasksDOM.addEventListener("click", async (event) => {
    const element = event.target; //clickすると要素を取得
    console.log(element.parentElement); //親要素(buttonタグ)も取得
    if (element.parentElement.classList.contains("delete-btn")) {
        const id = element.parentElement.dataset.id;
        console.log(id);
        try {
            await axios.delete(`/api/v1/tasks/${id}`);
            showTasks();
        } catch (err) {
            console.log(err);
        }
    }
});