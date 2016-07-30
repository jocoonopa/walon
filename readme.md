# walon

一場遊戲五個任務，最多可投票25次
目前遊玩平均約落在5~7次
分別研究投票次數對查出關聯性的正確影響

=======================================================================================================

#基本心態

##壞人一開始集體反對: 

發現我方派任務者都在順序後端，且接收任務者都是好人，避免遊戲過早結束，須盡量讓投票次數增加，
盡量獲取資訊判斷梅林或是擾亂視聽誤導派西維爾

##好人一開始集體反對:

拉長投票局，透過每次的投票質詢他人已挑出壞人，讓派西維爾辨認梅林和魔甘娜。

##壞人一開始集體贊成: 

一開始派發到任務者有壞人。

##好人一開始集體贊成: 

很碰運氣，派發任務者若不是梅林形同自殺。以玩家人數為8人時的情況來說，完全沒選到壞人機率僅為 5/28

小結: 細探每個人的投票是勝負關鍵。

=======================================================================================================

#測試資料

14次, 玩家人數8人, 好壞分配為 3/5

1:[A,B,D,H][C,E,F,G]X A
2:[A,C,D,H][B,E,F,G]X B
3:[B,D,F][A,C,E,G]X C
4:[C,E,F,H][A,B,D,G]X D
5:[B,D,F,G,H][A,C,E]O E v 
6:[A,E,F,G,H][B,C,D]O F
7:[F][A,B,C,D,E,G,H]X G
8:[A,C,D,E,G,H][B,F]O H
9:[A,C,D,E,G,H][B,F]O F v
10:[B][A,C,D,E,F,G,H]X A
11:[A,D,G][B,C,E,F,H]X B
12:[E][A,B,C,D,F,G,H]X C
13:[D,G,H][A,B,C,E,F]X D
14:[A,B,C,F,H][D,E,G]O E v

=======================================================================================================

#分析維度:
    - 總支持人數
    - 分別支持人數
    - 總支持曲線圖 -> 其形狀代表意義?
    - 分別支持點圖 -> 其形狀代表意義?
    - 從出任務者和目前任務成功結果分析誰可能是壞人

#維度Map:
    - 根據曲線形狀判斷此人為壞人或好人, 這個當然需要長時間紀錄數據才能分析，因此ABCDEFGH 這個必須要對應一個 User Model

=======================================================================================================

#功能:

預測那些人是同一國  => 好人有利
哪個人是梅林      => 壞人有利
哪個人是派西維爾  => 壞人有利
哪個人是魔甘娜    => 好人有利
哪個人是莫德雷德  => 好人有利
哪個人是奧伯倫    => 好壞其實都有利QQ

可代入已知參數分析, 例如自己是梅林，可以將壞人納入分析，幫助找出莫德雷德

1. A 為分析目標, 逐一計算出其他人對其贊成和反對的次數

B: 6  oxx,oxx,oxx,xxo,oo 42%
C: 10 xoo,xox,ooo,oxo,oo 70%
D: 9  oox,oxx,ooo,ooo,xx 63%
E: 8  xxo,xoo,ooo,oxx,ox 56%
F: 5  xxx,xxo,xxx,oxo,oo 35%
G: 8  xxo,oxo,ooo,ooo,xx 56%
H: 9  oox,xxo,ooo,oxo,xo 63%

- 總支持人數 
55 (我不曉得這樣算好還是壞，又或是根本沒有所謂好壞? 至少這數字越高應該表示他越被好人支持信任)

- 總支持曲線圖 -> 33332465562644

##實作:

1. 選出全部資料
2. 按照回合->贊成/反對 組成二維陣列
(此二維陣列在新一個投票資料注入後背景產生，儲存在某暫存方便調用)
3. 針對傳入的 Input::get('target'), 做所有已定義的維度分析, 圖表部分均使用折線圖顯示
4. 可根據指定回合(Input::get('assign_turns'))做即時運算分析。
5. 開牌後輸入並記錄每位玩家真實身分, 之後可以從此數據分析判斷玩家僻好習慣

#Build Steps

##資料庫建置

User: Please ref the migrations file

Character
    -name
    -is_jus

Game
    -players[Player]
    -missions[Mission]
    -is_own_by_jus[boolean: 正義或是邪惡獲勝]
    -timestamps

Player
    -.user
    -.game
    -.character

Mission
    - sn[integer: 任務編號]
    - is_success[boolean: 任務成功與否]
    - elections[Election]
    - participants[Player]

Election
    - host[Player]
    - is_pass[boolean: 是否通過]
    - votes[Vote]

Vote
    -voter[Player]
    -is_agree[boolean: 是否贊成]


1. 先把所有表格的關聯關係定義出來(1對1, 1對多, 多對多)
2. migration file
3. model 建立
4. 建立seed
5. migrate

##Controller

Game: CR
User: CRUD
Character: seed, no routes are needed
Mission: CRUD
Election: CRD
Vote: CRU

##View/Assets
http://lorempixel.com/

先用最簡單的 Bootstrap 樣式呈現
之後改成有 Ring (https://css-tricks.com/snippets/sass/placing-items-circle/
)和有圖片的版本,
上傳相片可透過cropper 剪裁