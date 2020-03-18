# Web_Team_Project(PHP)


# 결과
http://reesi.ohseon.com/
http://juhye9663.ohseon.com/


# 목적
지속적으로 유지보수되는 개발자 커뮤니티 사이트 구현


# 참여자
```
AwesomeDni 
hyejin-kim1108
juhye963
sdi9429
```


# 개발환경
```
운영체제 : windows
서버 : xampp (v3.2.4), Apache
개발 언어 : php(v7.2.26), html, css, js, jquery, bootstrap
DB : mysql(v5.7)
호스팅: 오선호스팅
형상관리도구 : Git-hub
```


# 구현한 기능( 구현한 사람)
```
## 유저
Create (hyejin-kim1108, juhye963)
Read   (hyejin-kim1108, juhye963)
Delete (juhye963)

## 인증

로그인   (hyejin-kim1108, juhye963, AwesomeDni)
로그아웃 (hyejin-kim1108, juhye963)

## 게시글

Create          (AwesomeDni)
Read            (AwesomeDni)
Update          (AwesomeDni)
Delete          (sdi9429,AwesomeDni)
검색             (AwesomeDni)
카테고리별 분류   (AwesomeDni)
페이징           (AwesomeDni)
이전,다음글 버튼  (AwesomeDni)

## 댓글

Create   (sdi9429)
Read     (sdi9429)
Updater  (sdi9429)
Delete   (sdi9429)

## 관리자권한

유저 관리 조건별 검색 (juhye963)
유저 관리 Read       (juhye963)
유저 관리 delete     (juhye963)
유저 관리 페이징 (juhye963)
게시글 관리 delete   (AwesomeDni)
댓글 관리 delete     (AwesomeDni)
카테고리 관리 Create (hyejin-kim1108)
카테고리 관리 Read   (hyejin-kim1108)
카테고리 관리 Delete (hyejin-kim1108)

## 마이페이지

페이징             (hyejin-kim1108)
자신이 쓴 글 보기   (hyejin-kim1108)
```


# 진행과정
```
01.18 : 게시글 create, read
01.31 : 회원가입, 로그인 - 초기페이지 구성 (sqlli_conn 이용한 문법 위주로 사용, 추후 PDO 문법으로 수정)
02.03 : 회원탈퇴(폼과 기본적인 기능의 틀),게시글 이전,다음글 버튼/ 게시글 조회수/ 로그아웃 쿠키 지우기, 게시글 삭제 기능구현
02.04 : 로그인, 회원탈퇴 수정, 비번 암호화, 게시글 페이징 기능 추가 
02.05 : 카테고리 추가, 삭제시 팝업창 띄운후 리스트로 이동 추가
02.07 : 메인, 마이페이지 - 초기페이지 구성
02.09 : 마이페이지 - 내가 쓴 글 리스트 작성, 로그인한 유저의 이름 나오게 수정
02.10 : 댓글 CRUD 기능구현
02.11 : 회원탈퇴 메시지, 실패시 탈퇴폼으로, 댓글 create
02.12 : 마이페이지 - 로그아웃시 자동으로 메인으로 가지 않는 버그 수정 ,카테고리별로 분류 기능 추가
02.13 : 메인 - 비회원 유저 마이페이지 접근 불가 설정 
        마이페이지 - 비회원 유저가 마이페이지로 바로 접근시, 메인으로 자동으로 리다이렉트 
                  - CSS 수정 
                  - 로그아웃시 메인으로 바로 이동 코드보강
        관리자 로그인 기능
02.14 : 관리자 로그인시 게시글 선택 삭제 기능
02.15 : 관리자 전용 게시판 관리페이지 - 현재 있는 카테고리 추가, 삭제등 관리 가능한 통합페이지 구성, 댓글 수정하는 입력 form 추가
02.17 : 검색기능
02.18 : 관리자아이디로 회원가입 막음(회원가입 수정), 카테고리 ceare, delete
02.19 : 유저관리페이지(관리자) 정렬, 일괄삭제기능, 댓글 read 
02.20 : 유저관리페이지 페이징, 검색기능
02.21 : 유저관리페이지,로그인회원가입회원탈퇴 부트스트랩적용
02.23 : 마이페이지 - 상단에 카테고리 나오게 수정 
02.24 : 마이페이지 - CSS 수정 
                  - 페이징 적용, 일부 태그 위치 변경 
                  - 페이징 보강 
        전체적인 페이지(메인 동영상,로그인,게시판 등..) CSS 수정 
        메인의 동영상은 제이쿼리 라이브러리 사용 
        마이페이지 
```



초보 개발자들이 만든 첫 프로젝트로서 개선해야 할 부분이 많은 프로젝트입니다.
여러 개선해야할 문제점이 보이신다면 말씀 부탁드립니다.


## 테스트계정
```
test계정 id: test
test계정 pw: test

# 관리자 로그인시 기존 글, 카테고리는 더미데이터 외에는 건들지 말아주실 바랍니다.

관리자 계정으로 로그인 방법

1. 로그인창 id에 admin 입력후 로그인 버튼 클릭
2. 관리자로 로그인 클릭
3. id: admin
4. pw: admin
5. 2차 pw: webproject
```