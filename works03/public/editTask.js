const taskIDDOM = document.querySelector(".task-edit-id");
const taskNameDOM = document.querySelector(".task-edit-name");
const editFormDOM = document.querySelector(".single-task-form");
const formAlertDOM = document.querySelector(".form-alert");
const taskCompletedDOM = document.querySelector(".task-edit-completed");


const params = window.location.search;
const id = new URLSearchParams(params).get("id"); //用意したクエリのIDを引数にする
console.log(id);

//1つの特定のタスクの表示
const showTask = async () => {
  try {
    const { data: task } = await axios.get(`/api/v1/tasks/${id}`);//taskの中のdata属性だけ欲しい。${id}は7行目で取得したid
    const { _id, completed, name } = task;//_idを取り出して画面に表示する為。completed,nameは一応取得
    taskIDDOM.textContent = _id;
    taskNameDOM.value = name;//value属性
    if(completed) {
      taskCompletedDOM.checked = true;
    }//編集画面で完了したものはチェックが入った状態を維持するようにする
  } catch (err) {
    console.log(err);
  }
};
showTask();

//タスクの編集
editFormDOM.addEventListener("submit", async (e) => {
  e.preventDefault();
  try {
    const taskName = taskNameDOM.value;
    taskCompleted = taskCompletedDOM.checked; //constは不要
    const { data: task } = await axios.patch(`/api/v1/tasks/${id}`, {
      name: taskName, //第二引数のtaskNameは更新後のタスク名
      completed: taskCompleted,//チェックがついているか否か
    });//idは7行目で取得したid
    formAlertDOM.computedStyleMap.display = "block";
    formAlertDOM.textContent = "編集に成功しました";
    formAlertDOM.classList.add("text-success");
  } catch (err) {
    console.log(err);
  }

  setTimeout(() => {
    formAlertDOM.style.display = "none";//（3秒後に）エラー文が消える
    formAlertDOM.classList.remove("text-success");//67行目のtext-successの色が反映されているので削除
  }, 3000);//3秒後にエラー文が消える
});